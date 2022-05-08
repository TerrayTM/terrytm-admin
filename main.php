<?php

require_once(__DIR__ . "/vendor/autoload.php");

if ($_SERVER['SERVER_NAME'] === AppLocation::$LOCAL_HOST) {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");

    if ($_SERVER['REQUEST_METHOD'] === "OPTIONS") {
        header("Access-Control-Allow-Headers: " . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
    }
}

require_once(__DIR__ . "/Partials/RequestValidator.php");
require_once(__DIR__ . "/Helpers/ValidateRequest.php");

if (isset($_SERVER['HTTP_API_ROUTE'])) {
    function load($name) {
        require_once(__DIR__ . "/Controllers/Services/" . $name . ".php");
    }

    switch ($_SERVER['HTTP_API_ROUTE']) {
        case "Analytics": load("Analytics");
        case "Message": load("Message");
        case "Project": load("Project");
        case "Blog": load("Blog");
        case "Navigation": load("Navigation");
        case "IndexProjects": load("IndexProjects");
        case "Build": load("Build");
        case "Steam": load("Steam");
        case "Error": load("Error");
        default: response_error("Invalid API route.");
    }
} else {
    http_response_code(404);
}

?>