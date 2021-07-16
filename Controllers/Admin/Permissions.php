<?php

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$redirect = "/permissions.php";
$response_data = [];

switch($_POST['request']) {
    case "create":
        json_decode($_POST['rules']);

        if (!file_exists(__DIR__ . "/../../Models/" . $_POST['model'] . ".php") || json_last_error() !== JSON_ERROR_NONE) {
            $redirect = "/permissions.php?error=true";
        } else {
            QueryPermission::create([ "model" => $_POST['model'], "rules" => $_POST['rules'] ]);
        }

        break;
    case "delete":
        QueryPermission::find($_POST['id'])->delete();

        break;
    case "download":
        require(__DIR__ . "/../../Helpers/DownloadCSV.php");

        download_csv(QueryPermission::class);

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