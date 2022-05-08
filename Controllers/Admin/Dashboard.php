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
        if ($_POST['type'] === "all") {
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
        require_once(__DIR__ . "/../../Config/Config.php");

        $candidate = config("secret") . json_encode([$_POST['location'], $_POST["expiry"]]) . config("secret");

        if (password_verify($candidate, $_POST["signature"]) && intval($_POST["expiry"]) > time()) {
            if (basename($_POST['location']) === "error_log" && is_file($_POST['location'])) {
                unlink($_POST['location']);
            }
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
        require_once(__DIR__ . "/../../Config/Config.php");

        $output = [];

        foreach (AppLocation::$error_logs as $location) {
            if (is_file($location)) {
                $content = file_get_contents($location);
                $expiry = strval(time() + 21600);

                $output[] = [
                    "location" => $location,
                    "content" => $content,
                    "expiry" => $expiry,
                    "signature" => password_hash(config("secret") . json_encode([$location, $expiry]) . config("secret"), PASSWORD_DEFAULT)
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
