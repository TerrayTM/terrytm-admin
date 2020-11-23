<?php

validate_request($_POST, [["success", "s"]]);

require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");
require_once(__DIR__ . "/../../Helpers/SendEmail.php");
require_once(__DIR__ . "/../../Config/Config.php");

if ($_POST['success'] !== "true") {
    validate_request($_POST, [
        ["log", "s"],
        ["timestamp", "s"],
        ["operation", "s"],
        ["pass", "n"]
    ]);

    $exception = null;

    if (isset($_POST['exception'])) {
        $exception = $_POST['exception'];
    }

    AppError::create([
        "json" => json_encode([
            "log" => $_POST['log'],
            "timestamp" => $_POST['timestamp'],
            "exception" => $exception,
            "operation" => $_POST['operation'],
            "pass" => $_POST['pass']
        ])
    ]);

    $push = Push::find($_POST['pass']);
    $mail_destination = config("email");

    if ($push && $push->email && strpos($push->email, "noreply") === false) {
        $mail_destination = $push->email;
    }

    send_email($mail_destination, config("email"), nl2br($_POST['log']), "Build Pipeline Error");

    response_success();
}

validate_request($_POST, [
    ["build", "s"],
    ["log", "s"],
    ["timestamp", "s"],
    ["duration", "n"],
    ["unitTests", "s"],
    ["setupCheck", "s"],
    ["twineCheck", "s"],
    ["pass", "n"]
]);

$duration = round(floatval($_POST['duration']), 2);

$build = Build::create([
    "is_successful" => $_POST['build'] === "true",
    "log" => $_POST['log'],
    "timestamp" => $_POST['timestamp'],
    "duration" => $duration,
    "tests_passed" => $_POST['unitTests'] === "true",
    "setup_passed" => $_POST['setupCheck'] === "true",
    "twine_passed" => $_POST['twineCheck'] === "true",
    "push_id" => $_POST['pass']
]);

$push = $build->find_parent();
$url = "??????";

if ($push) {
    $url = str_replace("git://", "", $push->url);
}

$message = "Repository - " . $url . "<br>";
$message .= "Unit Tests - " . ($build->tests_passed ? "Passed": "Failed") . "<br>";
$message .= "Setup Test - " . ($build->setup_passed ? "Passed": "Failed") . "<br>";
$message .= "Twine Test - " . ($build->twine_passed ? "Passed": "Failed") . "<br>";
$message .= "Duration - " . $build->duration . " Seconds <br>";
$message .= "<br>Full Console:<br><br>" . nl2br($_POST['log']);
$mail_destination = config("email");

if ($push && $push->email && strpos($push->email, "noreply") === false) {
    $mail_destination = $push->email;
}

send_email($mail_destination, config("email"), $message, "Build Report - " . ($build->is_successful ? "Success" : "Failed"));

$query = "Build-Passing-green";

if (!$build->is_successful) {
    $query = "Build-Failing-red";
}

if ($push) {
    file_put_contents(__DIR__ . "/../../../files/badges/" . $push->repository . ".svg", file_get_contents("https://img.shields.io/badge/" . $query));
} else {
    AppError::create([
        "json" => json_encode([
            "error" => "Invalid parent identifier. Failed to create build badge.",
            "build_id" => $build->id,
            "push_id" => $_POST['pass']
        ])
    ]);
}

response_success();

?>
