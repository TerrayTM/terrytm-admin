<?php

use Carbon\Carbon;

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$redirect = "/dashboard.php";

switch ($_POST['request']) {
    case "deleteTask":
        Task::find($_POST['id'])->delete();

        break;
    case "createTask":
        Task::create(["text" => $_POST["text"]]);
        
        break;
    case "deleteNote":
        Note::find($_POST['id'])->delete();

        break;
    case "createNote":
        Note::create(["note" => $_POST['note']]);

        break;
    case "editNote":
        $note = Note::find($_POST['id'])->update(["note" =>  $_POST['note']]);

        break;
    case "deleteAnalytics":
        if ($_POST['type'] === 'All') {
            Analytics::truncate();
        } else {
            Analytics::whereDate("timestamp", ">=", Carbon::now()->startOfMonth())->delete();
        }

        break;
    case "deleteMessages":
        Message::where("has_seen", false)->update(["has_seen" => true]);

        header("Location: " . $_POST['back']);

        exit();

        break;
    default:
        throw new Exception("Invalid request type.");
}

header("Location: " . $redirect);

exit();

?>