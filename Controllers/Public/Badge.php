<?php

validate_request($_GET, [
    ["name", "s"]
]);

$path = __DIR__ . "/../../../files/badges/" . basename($_GET['name']) . ".svg";

if (!file_exists($path)) {
    response_error("Badge not found.");
}

header("Content-Type: image/svg+xml");
header("Cache-Control: no-cache");

readfile($path);

exit();

?>
