<?php

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$redirect = "/projects.php";
$response_data = [];

switch($_POST['request']) {
    case "edit":
        Project::find($_POST['id'])->update([
            "name" => $_POST['name'],
            "type" => $_POST['type'],
            "date" => $_POST['date'],
            "author" => $_POST['author'],
            "description" => $_POST['description'],
            "link" => $_POST['link']
        ]);

        break;
    case "delete":
        require_once(__DIR__ . "/../../Config/Config.php");

        Cloudinary::config([
            "cloud_name" => config("cloudinary_name"), 
            "api_key" => config("cloudinary_api_key"), 
            "api_secret" => config("cloudinary_api_secret"), 
            "secure" => true
        ]);

        $api = new \Cloudinary\Api();
        $folder = "projects/" . $_POST['id'] . "/";

        $api->delete_resources_by_prefix($folder);

        Project::find($_POST['id'])->delete();

        break;
    case "deleteTechnology":
        ProjectTechnology::find($_POST['id'])->delete();

        break;
    case "deleteTag":
        ProjectTag::find($_POST['id'])->delete();

        break;
    case "createTechnology":
        $item = ProjectTechnology::create([
            "project_id" => $_POST['id'],
            "technology" => $_POST['technology']
        ]);

        $response_data['id'] = $item->id;

        break;
    case "createTag":
        $item = ProjectTag::create([
            "project_id" => $_POST['id'],
            "name" => $_POST['value'],
            "color" => $_POST['color']
        ]);

        $response_data['id'] = $item->id;

        break;
    case "order":
        $images = json_decode($_POST['images']);
        $redirect = "/projects.php?edit=" . $_POST['id'];

        for ($i = 0; $i < count($images); ++$i) {
            ProjectImage::find($images[$i])->update(["order" => $i + 1]);
        }

        break;
    case "deleteImage":
        ProjectImage::find($_POST['id'])->delete();

        // To Do
        // Delete image CDN
        // Update image order

        break;
    case "create":
        $project = Project::create([
            "name" => $_POST['name'],
            "type" => $_POST['type'],
            "date" => $_POST['date'],
            "author" => $_POST['author'],
            "description" => $_POST['description'],
            "link" => $_POST['link']
        ]);

        $redirect = "/projects.php?edit=" . $project->id;

        break;
    case "upload":
        require_once(__DIR__ . "/../../Config/Config.php");

        Cloudinary::config([
            "cloud_name" => config("cloudinary_name"), 
            "api_key" => config("cloudinary_api_key"), 
            "api_secret" => config("cloudinary_api_secret"), 
            "secure" => true
        ]);

        $image = ProjectImage::where("project_id", $_POST['id'])->orderBy("order", "DESC")->first();
        $counter = $image ? intval($image->order) + 1 : 1;

        for ($i = 0; $i < count($_FILES['images']['name']); ++$i, ++$counter) {
            $data = \Cloudinary\Uploader::upload($_FILES['images']['tmp_name'][$i], ["folder" => "projects/" . $_POST['id'] . "/"]);

            ProjectImage::create([
                "order" => $counter,
                "url" => $data['secure_url'],
                "project_id" => $_POST['id']
            ]);
        }

        $redirect = "/projects.php?edit=" . $_POST['id'];

        break;
    case "download":
        require(__DIR__ . "/../../Helpers/DownloadCSV.php");

        download_csv(Project::class, ProjectImage::class, ProjectTag::class, ProjectTechnology::class);

        return;
    default:
        throw new Exception("Invalid request type.");
}

if (isset($_POST['async'])) {
    require(__DIR__ . "/../../Helpers/Response.php");

    response_success($response_data);
} else {
    header("Location: " . $redirect);
}

?>