<?php

require_once(__DIR__ . "/../Partials/DatabaseConnector.php");
require_once(__DIR__ . "/../Config/Config.php");

Cloudinary::config([
    "cloud_name" => config("cloudinary_name"), 
    "api_key" => config("cloudinary_api_key"), 
    "api_secret" => config("cloudinary_api_secret"), 
    "secure" => true
]);

if (!isset($_SERVER['REQUEST_METHOD'])) {
    $folders = array_diff(scandir(__DIR__ . "/../migrate-images"), [".", ".."]);

    foreach ($folders as $folder) {
        $path = __DIR__ . "/../migrate-images/" . $folder;
        $files = array_diff(scandir($path), [".", ".."]);
        $project = Project::where("name", str_replace("-", " ", $folder))->first();

        if (!$project) {
            echo("Skipping " . $folder . "...\n");

            continue;
        }

        $picture = $project->images()->orderBy("order", "DESC")->first();
        $counter = $picture ? intval($picture->order) : 1;

        foreach ($files as $file) {
            $folder = strtolower(str_replace(" ", "-", $folder));
            $data = \Cloudinary\Uploader::upload($path . "/" . $file, ["folder" => "projects/" . $folder . "/"]);

            ProjectImage::create(["order" => $counter, "url" => $data['secure_url'], "project_id" => $project->id]);

            echo("Moved " . $file . " in " . $folder . "! Order number is " . $counter . ".\n");

            ++$counter;
        }
    }
}

?>