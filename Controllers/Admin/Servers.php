<?php

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$redirect = "/servers.php";

switch($_POST['request']) {
    case "create":
        Server::create([ "url" => $_POST['url'] ]);

        break;
    case "delete":
        Server::find($_POST['id'])->delete();

        break;
    default:
        throw new Exception("Invalid request type.");
}

header("Location: " . $redirect);

exit();

?>