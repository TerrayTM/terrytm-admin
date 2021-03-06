<?php

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$redirect = "/files.php";
$response_data = [];

switch($_POST['request']) {
    case "delete":
        unlink(__DIR__ . "/../../../files/" . $_POST['name']);

        break;
    case "create":
        move_uploaded_file($_FILES['file']['tmp_name'], __DIR__ . "/../../../files/" . $_FILES['file']['name']);

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