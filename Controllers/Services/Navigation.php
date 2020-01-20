<?php

require_once(__DIR__ . '/../../Partials/DatabaseConnector.php');

$projects = Project::select(["name", "type", "date"])->orderBy("date", "DESC")->get();
$blog = Blog::select(["name", "type", "date"])->orderBy("date", "DESC")->get();

$projects->transform(function ($item) {
    $item['url'] = $item->url(true);

    unset($item['date']);

    return $item;
});

$active = [];
$past = [];

foreach ($projects as $project) {
    $type = $project->type;

    unset($project['type']);

    if ($type === "Active Project") {
        $active[] = $project;
    } else {
        $past[] = $project;
    }
}

response_success([
    "blog" => [
        "Academics" => [],
        "Entertainment" => [],
        "Life Things" => []
    ],
    "projects" => [
        "active" => $active,
        "past" => $past
    ]
]);

?>