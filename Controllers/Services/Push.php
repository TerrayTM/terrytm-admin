<?php

header("Access-Control-Allow-Origin: *");

require_once(__DIR__ . "/../../Helpers/Response.php");
require_once(__DIR__ . "/../../Config/Config.php");

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    response_error("Invalid request method.");
}

if (!isset($_SERVER['HTTP_X_HUB_SIGNATURE'])) {
    response_error("Signature is required.");
}

$parts = explode("=", $_SERVER['HTTP_X_HUB_SIGNATURE'], 2);

if (count($parts) != 2) {
    response_error("Unknown signature type.");
}

$algorithm = $parts[0];
$hash = $parts[1];
$raw = file_get_contents("php://input");

if (!hash_equals($hash, hash_hmac($algorithm, $raw, config("github_secret")))) {
    response_error("Signature is invalid.");
}

if (!isset($_SERVER['HTTP_X_GITHUB_EVENT'])) {
    response_error("Unsupported Github event.");
}

if (strtolower($_SERVER['HTTP_X_GITHUB_EVENT']) === "ping") {
    response_success(["message" => "pong"]);
} else if (strtolower($_SERVER['HTTP_X_GITHUB_EVENT']) !== "push") {
    response_error("Unsupported Github event.");
}

if (!isset($_SERVER['CONTENT_TYPE']) || $_SERVER['CONTENT_TYPE'] !== "application/x-www-form-urlencoded") {
    response_error("Unsupported content type.");
}

require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$json = json_decode($_POST['payload']);
$email = null;

if ($json->ref !== "refs/heads/master") {
    response_success(["message" => "Unsupported branch."]);
}

if (isset($json->pusher->email) && $json->pusher->email) {
    $email = $json->pusher->email;
}

Push::create([
    "repository" => $json->repository->name,
    "url" => $json->repository->git_url,
    "user" => $json->pusher->name,
    "email" => $email
]);

response_success(["message" => "Logged successfully."]);

?>
