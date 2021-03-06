<?php

require_once(__DIR__ . "/../Helpers/SSLTest.php");
require_once(__DIR__ . "/../Partials/DatabaseConnector.php");

$watch_items = SSL::all();
$success = true;

foreach ($watch_items as $item) {
    $is_valid = ssl_test($item->url);

    $item->is_valid = $is_valid;

    $item->save();

    $success &= $is_valid;
}

CronResult::create([
    "type" => CronType::$ValidateSSL,
    "is_successful" => $success
]);

if (!$success) {
    require_once(__DIR__ . "/../Helpers/SendEmail.php");
    require_once(__DIR__ . "/../Config/Config.php");

    send_email(config("email"), config("email"), "SSL validation failed!", "Service Required");
}

?>