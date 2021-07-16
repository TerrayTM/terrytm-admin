<?php

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$redirect = "/calendar.php";
$response_data = [];

switch($_POST['request']) {
    case "load":
        $response_data['events'] = CalendarEvent::where("end_date", ">=", gmdate("Y-m-d H:i:s", strtotime("last month")))->get()->toArray();

        break;
    case "create":
        CalendarEvent::create([
            "start_date" => $_POST['start_date'],
            "end_date" => $_POST['end_date'],
            "event_id" => $_POST['id'],
            "text" => $_POST['text']
        ]);

        break;
    case "delete":
        CalendarEvent::where("event_id", $_POST['id'])->delete();

        break;
    case "edit":
        CalendarEvent::where("event_id", $_POST['id'])->update([
            "start_date" => $_POST['start_date'],
            "end_date" => $_POST['end_date'],
            "event_id" => $_POST['id'],
            "text" => $_POST['text']
        ]);

        break;
    case "download":
        require(__DIR__ . "/../../Helpers/DownloadCSV.php");

        download_csv(CalendarEvent::class);

        return;
    default:
        throw new Exception("Invalid request type.");
}

if (isset($_POST['async'])) {
    require(__DIR__ . "/../../Helpers/Response.php");

    response_success($response_data);
} else {
    header("Location: " . $redirect);
}

?>
