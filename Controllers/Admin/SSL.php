<?php

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$redirect = "/ssl.php";
$response_data = [];

switch($_POST['request']) {
    case "create":
        SSL::create([ "url" => $_POST['url'] ]);

        break;
    case "delete":
        SSL::find($_POST['id'])->delete();

        break;
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