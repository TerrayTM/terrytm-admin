<?php

validate_request($_POST, [
    ["name", "s"],
    ["email", "e"],
    ["message", "s"]
]);

require_once(__DIR__ . '/../../Config/Config.php');
require_once(__DIR__ . '/../../Helpers/SendEmail.php');
require_once(__DIR__ . '/../../Partials/DatabaseConnector.php');

Message::create([
    "name" => $_POST['name'],
    "email" => $_POST['email'],
    "message" => $_POST['message']
]);

if (!send_email(config("email"), config("email"), "Name: " . $_POST['name'] . "<br>Email: " . $_POST['email'] . "<br><br>Message:<br>" . $_POST['message'])) {
    response_error("Message failed to send.");
}

response_success();

?>