<?php

require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$projects = Project::select(["id", "name", "description", "date", "type"])->orderBy("date", "DESC")->with(["images" => function ($query) {
    $query->orderBy("order");
}])->get();

$projects->transform(function ($item) {
    $item['url'] = $item->url(true);
    $item['image'] = $item->images[0]->url ?? null;

    unset($item['date']);
    unset($item['type']);
    unset($item['id']);
    unset($item['images']);

    return $item;
});

response_success($projects);

?>