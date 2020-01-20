<?php

$debug = false;

if (isset($debug) && $debug) {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: API-Token, API-Test-Token, API-Route");
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
        case "Navigation": load("Navigation");
        case "Error": load("Error");
        case "IndexProjects": load("IndexProjects");
        default: response_error("Invalid API route.");
    }
} else {
    response_error("Missing request route header.");
}

?>