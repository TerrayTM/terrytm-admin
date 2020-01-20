<?php

validate_request($_POST, [
    ["json", "s"]
]);

require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

AppError::create([
    "json" => $_POST['json']
]);

response_success();

?>