<?php

require_once(__DIR__ . "/../Partials/DatabaseConnector.php");

$watch_items = SSL::where("is_valid", true)->get();
$success = true;

foreach ($watch_items as $item) {
    $fail = false;

    try {
        $fail = strlen(@file_get_contents($item->url)) === 0;
    } catch (Exception $exception) {
        $fail = true;
    }

    if ($fail) {
        $item->is_valid = false;
        $item->save();
    }

    $success &= !$fail;
}

CronResult::create([
    "type" => CronType::$VALIDATE_SSL,
    "is_successful" => $success
]);

if (!$success) {
    require_once(__DIR__ . "/../Helpers/SendEmail.php");
    require_once(__DIR__ . "/../Config/Config.php");

    send_email(config("email"), config("email"), "SSL validation failed!", "Service Required");
}

?>