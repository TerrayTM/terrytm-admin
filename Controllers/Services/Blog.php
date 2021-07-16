<?php

validate_request($_POST, [
    ["type", "s"],
    ["name", "s"]
]);

require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$post = Blog::select([
    "name",
    "type",
    "date",
    "author",
    "content"
])->where([
    "type" => $_POST['type'],
    "name" => $_POST['name']
])->first();

if (!$post) {
    response_error("Post not found.");
} else {
    $data = $post->toArray();

    response_success($data);
}

?>
