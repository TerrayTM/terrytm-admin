<?php

require_once(__DIR__ . "/TokenProvider.php");

$valid = false;

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $candidate = null;

    if (isset($_SERVER['HTTP_API_TOKEN'])) {
        $candidate = $_SERVER['HTTP_API_TOKEN'];
    } else if (isset($_POST['token'])) {
        $candidate = $_POST['token'];
    } else if (isset($_SERVER['HTTP_API_TEST_TOKEN'])) {
        require_once(__DIR__ . "/../Config/Config.php");

        $candidate = $_SERVER['HTTP_API_TEST_TOKEN'];
        $valid = $candidate === config("test_token");
    }

    $valid |= $candidate === $token;

    if (!$valid) {
        require_once(__DIR__ . "/DatabaseConnector.php");

        $valid = Token::if_valid_then_consume($candidate);
    }
}

if (!$valid) {
    require_once(__DIR__ . "/../Helpers/Response.php");

    response_error("Invalid token or request method.");
}

?>
