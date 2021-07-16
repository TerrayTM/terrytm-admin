<?php

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$redirect = "/analytics.php";
$response_data = [];

switch($_POST['request']) {
    case "delete":
        Analytics::where("url", $_POST['url'])->delete();

        $redirect = $_POST['referrer'];

        break;
    case "download":
        require(__DIR__ . "/../../Helpers/DownloadCSV.php");

        download_csv(Analytics::class);

        return;
    case "lookup":
        // To do: https://ipapi.co/{ip}/json/

        $redirect = $_POST['referrer'];


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