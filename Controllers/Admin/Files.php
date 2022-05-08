<?php

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$redirect = "/files.php";
$response_data = [];

switch($_POST['request']) {
    case "delete":
        $target = __DIR__ . "/../../../files/" . basename($_POST['name']);
        
        if (is_file($target)) {
            unlink($target);
        } else {
            AppError::create([
                "json" => json_encode([
                    "operation" => "delete",
                    "controller" => "files",
                    "target" => $target
                ])
            ]);
        }

        break;
    case "create":
        move_uploaded_file($_FILES['file']['tmp_name'], __DIR__ . "/../../../files/" . basename($_FILES['file']['name']));

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
