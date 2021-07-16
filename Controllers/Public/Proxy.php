<?php
// To do
validate_request($_GET, [
    ["id", "s"]
]);

require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$server = Server::where($_GET['id']);

if (!$server) {
    response_error("Invalid identifier.");
}

response_success();

?>

