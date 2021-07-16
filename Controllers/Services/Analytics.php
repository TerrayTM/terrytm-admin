<?php

validate_request($_POST, [
    ["url", "s"],
    ["isError", "s"],
    ["group", "s"]
]);

require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");
require_once(__DIR__ . "/../../Helpers/GetAddress.php");

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$referrer = "Origin";

if (isset($_SESSION['referrer'])) {
    $referrer = $_SESSION['referrer'];
}

Analytics::create([
    "url" => $_POST['url'],
    "group" => $_POST['group'],
    "referrer" => $referrer,
    "address" => get_address(),
    "is_error" => $_POST['isError'] === "true"
]);

response_success();

?>
