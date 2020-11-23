<?php

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$redirect = "/builds.php";
$response_data = [];

switch($_POST['request']) {
    case "download":
        require(__DIR__ . "/../../Helpers/DownloadCSV.php");

        download_csv(Build::class);

        return;
    case "retry":
        $build = Build::find($_POST['id']);
        $push = $build->find_parent();

        if (!$push) {
            $redirect .= "?success=false";
        } else {
            $push->is_built = false;

            $push->save();
            $build->delete();

            $redirect .= "?success=true";
        }

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