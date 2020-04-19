<?php

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Helpers/ValidateRequest.php");

$redirect = "/tools.php";
$response_data = [];

switch($_POST['request']) {
    case "email":
        validate_request($_POST, [
            ["to", "e"],
            ["message", "s"],
            ["subject", "s"]
        ]);

        require_once(__DIR__ . "/../../Helpers/SendEmail.php");
        require_once(__DIR__ . "/../../Config/Config.php");

        try {
            $result = send_email($_POST['to'], config("email"), $_POST['message'], $_POST['subject']);
            $response_data['output'] = $result ? "Email sent successfully!" : "Email has failed to send.";
        } catch (Exception $exception) {
            $response_data['output'] = $exception->getMessage();
        }

        break;
    case "ping":
        validate_request($_POST, [
            ["url", "s"]
        ]);

        try{
            if (strlen(file_get_contents($_POST['url'])) === 0) {
                $response_data['output'] = "Ping failed! No response received.";
            } else {
                $response_data['output'] = "Ping success!";
            }
        } catch (Exception $exception) {
            $response_data['output'] = $exception->getMessage();
        }

        break;
    case "job":
            validate_request($_POST, [
                ["name", "s"]
            ]);

            $jobs = __DIR__ . "/../../Jobs";
            $file = $_POST['name'] . ".php";

            if (in_array($file, scandir($jobs), true)) {
                require_once($jobs . "/" . $file);
                require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

                $result = CronResult::latest("timestamp")->first();
                
                if ($result && time() - strtotime($result->timestamp) <= 30) {
                    $response_data['output'] = "Job finished execution at " . $result->timestamp . " with " . ($result->is_successful ? "success" : "failure") . ".";
                } else {
                    $response_data['output'] = "Job execution failed.";
                }
            } else {
                $response_data['output'] = "Job not found.";
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