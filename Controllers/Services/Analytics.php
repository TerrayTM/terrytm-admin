<?php

validate_request($_POST, [
    ["url", "s"],
    ["group", "s"],
    ["isError", "s"]
]);

require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

Analytics::create([
    "url" => $_POST['url'],
    "group" => $_POST['group'],
    "is_error" => $_POST['isError'] === "true"
]);

response_success();

?>