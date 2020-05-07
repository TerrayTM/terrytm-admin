<?php

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
        case "IndexProjects": load("IndexProjects");
        case "Build": load("Build");
        case "Error": load("Error");
        default: response_error("Invalid API route.");
    }
} else {
    http_response_code(404);
}

?>