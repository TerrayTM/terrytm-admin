<?php

validate_request($_GET, [
    ["route", "s"],
    ["name", "s"]
]);

$path = __DIR__ . "/../../../files/badges/" . $_GET['name'] . ".svg";

if (file_exists($path)) {
    header("Content-Type: image/svg+xml");
    header("Cache-Control: no-cache");

    readfile($path);

    exit();
}

response_error("Badge not found.");

?>
