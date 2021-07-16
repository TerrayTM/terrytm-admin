<?php

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$redirect = "/requests.php";
$response_data = [];

switch($_POST['request']) {
    case "create":
        json_decode($_POST['body']);

        if (!filter_var($_POST['url'], FILTER_VALIDATE_URL) || json_last_error() !== JSON_ERROR_NONE) {
            $redirect = "/requests.php?error=true";
        } else {
            Request::create([ "url" => $_POST['url'], "json" => $_POST['body'] ]);
        }

        break;
    case "delete":
        Request::find($_POST['id'])->delete();

        break;
    case "download":
        require(__DIR__ . "/../../Helpers/DownloadCSV.php");

        download_csv(Request::class);

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
