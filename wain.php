<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

function load($name) {
    require_once(__DIR__ . "/Controllers/Public/" . $name . ".php");
}

switch ($_SERVER['REQUEST_METHOD']) {
    case "POST":
        require_once(__DIR__ . "/Helpers/ValidateRequest.php");
        require_once(__DIR__ . "/Partials/ContentTransformer.php");

        if (isset($_SERVER['HTTP_API_ROUTE'])) {
            switch ($_SERVER['HTTP_API_ROUTE']) {
                case "Message": load("Message");
                default: response_error("Invalid API route.");
            }
        } else if (isset($_SERVER['HTTP_X_GITHUB_DELIVERY'])) {
            load("Push");
        } else {
            http_response_code(404);
        }

        break;
    case "GET":
        require_once(__DIR__ . "/Helpers/ValidateRequest.php");

        if (isset($_GET['route'])) {
            switch ($_GET['route']) {
                case "badge": load("Badge");
                case "proxy": load("Proxy");
                case "query": load("Query");
                default: response_error("Invalid GET route.");
            }
        } else {
            http_response_code(404);
        }

        break;
    case "OPTIONS":
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header("Access-Control-Allow-Headers: " . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
        }

        break;
    default:
        http_response_code(404);

        break;
}

?>
