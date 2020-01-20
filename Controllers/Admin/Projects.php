<?php

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$redirect = "/projects.php";

switch($_POST['request']) {
    case "edit":
        Project::find($_POST['id'])->update([
            "name" => $_POST['name'],
            "type" => $_POST['type'],
            "date" => $_POST['date'],
            "author" => $_POST['author'],
            "description" => $_POST['description'],
            "link" => $_POST['link']
        ]);

        break;
    case "delete":
        Project::find($_POST['id'])->delete();

        break;
    case "create":
        Project::create([
            "name" => $_POST['name'],
            "type" => $_POST['type'],
            "date" => $_POST['date'],
            "author" => $_POST['author'],
            "description" => $_POST['description'],
            "link" => $_POST['link']
        ]);

        break;
    default:
        throw new Exception("Invalid request type.");
}

header("Location: " . $redirect);

exit();

?>