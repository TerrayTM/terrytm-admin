<?php

$start_time = microtime(true);

require_once(__DIR__ . "/../Partials/DatabaseConnector.php");
require_once(__DIR__ . "/../Helpers/WithReconnect.php");
require_once(__DIR__ . "/../Helpers/SendEmail.php");
require_once(__DIR__ . "/../Config/Config.php");

use Carbon\Carbon;

$events = CalendarEvent::where("start_date", ">", gmdate("Y-m-d H:i:s"))->where("start_date", "<=", gmdate("Y-m-d H:i:s", time() + 10800))->get();
$overall_result = true;
$job_log = null;

foreach ($events as $event) {
    $email_list = [config("email")];
    $event_text = $event->text;

    preg_match_all("/\\+(\\S+)/", $event_text, $matches);

    if (count($matches) > 0) {
        $email_list = array_merge($email_list, $matches[1]);

        foreach ($matches[0] as $found) {
            $event_text = str_replace($found, "", $event_text); 
        }

        $event_text = rtrim($event_text);
    }

    foreach ($email_list as $email) {
        $notification = CalendarNotification::where("event_id", $event->id)->where("email", $email)->first();

        if (!$notification) {
            $notification = CalendarNotification::create([
                "email" => $email,
                "event_id" => $event->id,
                "should_notify" => true
            ]);
        }

        if ($notification->should_notify) {
            $token = Token::generate();
            $expiry = strval(time() + 43200);
            $signature = password_hash(config("secret") . json_encode(["", "notification", $token, $expiry, strval($notification->id)]) . config("secret"), PASSWORD_DEFAULT);
            $link = "https://terrytm.com/notification/" . $token . "/" . $expiry . "/" . $notification->id . "/" . rawurlencode($signature);
            $date = date("Y-m-d h:iA", strtotime($event->start_date . " UTC"));
            
            $message = "A scheduled event is happening in " . Carbon::parse($event->start_date . " UTC")->diffForHumans() . "!<br><br>";
            $message .= "<b>Start Time</b> - " . $date . "<br>";
            $message .= "<b>End Time</b> - " . date("Y-m-d h:iA", strtotime($event->end_date . " UTC")) . "<br><br>";
            $message .= '<b>Description:</b><br><br>' . nl2br($event_text) . '<br><br><hr style="border: 1px solid cyan; border-bottom: none;">';
            $message .= "Reminders will be sent every 30 minutes before the start of the event.<br>";
            $message .= '<a href="' . $link . '" target="_blank">Click here to unsubscribe from further notifications for this event.</a>';
            
            if (!send_email($email, config("email"), $message, "Event Reminder - " . $date)) {
                $overall_result = false;
                $job_log = ($job_log ?? "") . " " . $email;
            }
        }
    }
}

with_reconnect(function () use ($start_time, $overall_result, $job_log) {
    CronResult::create([
        "type" => CronType::$SendNotification,
        "is_successful" => $overall_result,
        "message" => $job_log,
        "duration" => microtime(true) - $start_time
    ]);
});

?>
