<?php

use Carbon\Carbon;

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$redirect = "/dashboard.php";
$response_data = [];

switch ($_POST['request']) {
    case "deleteTask":
        Task::find($_POST['id'])->delete();

        break;
    case "createTask":
        $task = Task::create(["text" => $_POST['text']]);
        $response_data['id'] = $task->id;
        
        break;
    case "deleteNote":
        Note::find($_POST['id'])->delete();

        break;
    case "createNote":
        Note::create(["note" => $_POST['note']]);

        break;
    case "editNote":
        Note::find($_POST['id'])->update(["note" => $_POST['note']]);

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

    case "deleteError":
        if (is_file($_POST['location'])) {
            unlink($_POST['location']);
        }

        break;
    case "createAccount":
        Account::create([
            "name" => $_POST['name'],
            "username" => $_POST['username'],
            "password" => $_POST['password']
        ]);

        break;
    case "deleteAccount":
        Account::find($_POST['id'])->delete();

        break;
    case "validateMaster":
        require_once(__DIR__ . "/../../Config/Config.php");

        $response_data['valid'] = hash_equals(config("master_hash"), $_POST['hash']);

        break;
    case "checkErrors":
        $output = [];

        foreach (AppLocation::$error_logs as $location) {
            if (is_file($location)) {
                $content = file_get_contents($location);

                $output[] = [
                    "location" => $location,
                    "content" => $content
                ];
            }
        }
        
        $response_data['errors'] = $output;

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