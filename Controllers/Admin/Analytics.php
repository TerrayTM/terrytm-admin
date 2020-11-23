<?php

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$redirect = "/analytics.php";
$response_data = [];

switch($_POST['request']) {
    case "delete":
        Analytics::where("url", $_POST['url'])->delete();

        break;
    case "download":
        require(__DIR__ . "/../../Helpers/DownloadCSV.php");

        download_csv(Analytics::class);

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