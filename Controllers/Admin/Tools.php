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
    case "ssl":
        validate_request($_POST, [
            ["url", "s"]
        ]);

        require_once(__DIR__ . "/../../Helpers/SSLTest.php");

        if (ssl_test($_POST['url'])) {
            $response_data['output'] = "Valid SSL!";
        } else {
            $response_data['output'] = "Invalid or missing SSL.";
        }

        break;
    case "job":
            validate_request($_POST, [
                ["name", "s"]
            ]);

            $jobs = __DIR__ . "/../../Jobs";
            $file = $_POST['name'] . ".php";
            $suppress_sleep = true;

            if (in_array($file, scandir($jobs), true)) {
                require_once($jobs . "/" . $file);
                require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

                $result = CronResult::where("type", $_POST['name'])->latest("timestamp")->first();
                
                if ($result && time() - strtotime($result->timestamp) <= 30) {
                    $response_data['output'] = "Job finished execution at " . $result->timestamp . " with " . ($result->is_successful ? "success" : "failure") . ".";
                } else {
                    $response_data['output'] = "Job execution failed.";
                }
            } else {
                $response_data['output'] = "Job not found.";
            }
    
            break;
    case "address":
        validate_request($_POST, [
            ["cloudflare", "s"]
        ]);

        require(__DIR__ . "/../../Helpers/GetAddress.php");

        $response_data['output'] = get_address($_POST['cloudflare'] === "true");
        
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