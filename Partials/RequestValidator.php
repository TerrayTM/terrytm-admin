<?php

require_once(__DIR__ . "/TokenProvider.php");
require_once(__DIR__ . "/../Config/Config.php");

$valid = false;

if($_SERVER['REQUEST_METHOD'] === "POST") {
    $valid |= isset($_SERVER['HTTP_API_TOKEN']) && $_SERVER['HTTP_API_TOKEN'] === $token;
    $valid |= isset($_POST['token']) && $_POST['token'] === $token;
    $valid |= isset($_SERVER['HTTP_API_TEST_TOKEN']) && $_SERVER['HTTP_API_TEST_TOKEN'] === config("test_token");
}

if (!$valid) {
    require_once(__DIR__ . "/../Helpers/Response.php");

    response_error("Invalid token or request method.");
}

?>