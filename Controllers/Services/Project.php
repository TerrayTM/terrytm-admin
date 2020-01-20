<?php

validate_request($_POST, [
    ["type", "s"],
    ["name", "s"]
]);

require_once(__DIR__ . '/../../Partials/DatabaseConnector.php');

$project = Project::where([
    "type" => $_POST['type'],
    "name" => $_POST['name']
])->with(["tags", "technologies", "images" => function ($query) { 
    $query->orderBy("order");
}])->first();

if (!$project) {
    response_error("Project not found.");
} else {
    $data = $project->toArray();
    $data['images'] = $project->images->pluck("url");
    $data['technologies'] = $project->technologies->pluck("technology");

    unset($data['id']);

    response_success($data);
}

?>