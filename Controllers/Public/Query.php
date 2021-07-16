<?php

validate_request($_GET, [
    ["model", "s"]
]);

require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$parameters = $_GET;

unset($parameters['route']);
unset($parameters['model']);

foreach (QueryPermission::where("model", $_GET['model'])->get() as $query_permission) {
    if ($query_permission->is_allowed($parameters)) {
        $result = $_GET['model']::where($parameters)->get()->toArray();

        if (count($result) > 0) {
            response_success($result);
        } else {
            response_error("Query returned no results.");
        }

        break;
    }
}

response_error("Parameters are invalid.");

?>
