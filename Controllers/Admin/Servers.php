<?php

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$redirect = "/servers.php";
$response_data = [];

switch($_POST['request']) {
    case "create":
        Server::create([ "url" => $_POST['url'] ]);

        break;
    case "delete":
        Server::find($_POST['id'])->delete();

        break;
    case "wake":
        require_once(__DIR__ . "/../../Helpers/WakeServer.php");

        $response_data['success'] = wake_server(Server::find($_POST['id'])->url . "wake");

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