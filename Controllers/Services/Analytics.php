<?php

validate_request($_POST, [
    ["url", "s"],
    ["group", "s"],
    ["isError", "s"]
]);

require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$address = "UNKNOWN";

if (isset($_SERVER['HTTP_CLIENT_IP'])) {
    $address = $_SERVER['HTTP_CLIENT_IP'];
} else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $address = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else if(isset($_SERVER['HTTP_X_FORWARDED'])) {
    $address = $_SERVER['HTTP_X_FORWARDED'];
} else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
    $address = $_SERVER['HTTP_FORWARDED_FOR'];
} else if(isset($_SERVER['HTTP_FORWARDED'])) {
    $address = $_SERVER['HTTP_FORWARDED'];
} else if(isset($_SERVER['REMOTE_ADDR'])) {
    $address = $_SERVER['REMOTE_ADDR'];
}

Analytics::create([
    "url" => $_POST['url'],
    "group" => $_POST['group'],
    "address" => $address,
    "is_error" => $_POST['isError'] === "true"
]);

response_success();

?>