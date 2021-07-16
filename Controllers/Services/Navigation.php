<?php

require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$projects = Project::select(["name", "type", "date"])->orderBy("date", "DESC")->get();
$blog = Blog::select(["name", "type", "date"])->orderBy("date", "DESC")->get();

$projects->transform(function ($item) {
    $item['url'] = $item->url(true);

    unset($item['date']);

    return $item;
});

$blog->transform(function ($item) {
    $item['url'] = $item->url(true);

    unset($item['date']);

    return $item;
});

$project_response = [];

foreach ($projects as $project) {
    $type = $project->type;

    unset($project['type']);

    if (!isset($project_response[$type])) {
        $project_response[$type] = [];
    }

    $project_response[$type][] = $project;
}

$blog_response = [];

foreach ($blog as $post) {
    $type = $post->type;

    unset($post['type']);

    if (!isset($blog_response[$type])) {
        $blog_response[$type] = [];
    }

    $blog_response[$type][] = $post;
}

response_success([
    "blog" => $blog_response,
    "projects" => $project_response
]);

?>