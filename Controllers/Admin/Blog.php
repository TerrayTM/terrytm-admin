<?php

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

function process_post_content($post = null) {
    require_once(__DIR__ . "/../../Config/Config.php");

    Cloudinary::config([
        "cloud_name" => config("cloudinary_name"), 
        "api_key" => config("cloudinary_api_key"), 
        "api_secret" => config("cloudinary_api_secret"), 
        "secure" => true
    ]);

    $content = json_decode($_POST['content']);
    $image_identifiers = [];

    if (!$post) {
        $post = Blog::create([
            "name" => $_POST['name'],
            "type" => $_POST['type'],
            "date" => $_POST['date'],
            "author" => $_POST['author'],
            "content" => $_POST['content']
        ]);
    } else {
        $post->update([
            "name" => $_POST['name'],
            "type" => $_POST['type'],
            "date" => $_POST['date'],
            "author" => $_POST['author'],
            "content" => $_POST['content']
        ]);
    }

    foreach ($content->blocks as $block) {
        if ($block->type === "image") {
            $image_identifiers[] = $block->data->file->id;

            if (!$block->data->file->processed) {
                $image = BlogImage::find($block->data->file->id);
                $path = __DIR__ . "/../../../files/images/temporary/image-" . $image->id . ".jpg";

                if (!$image || !file_exists($path)) {
                    AppError::create([
                        "json" => json_encode([
                            "content" => $_POST['content'],
                            "id" => $block->data->file->id,
                            "path" => $path,
                            "post" => $post->id
                        ])
                    ]);

                    continue;
                }
                
                $data = \Cloudinary\Uploader::upload($path, ["folder" => "blog/" . $post->id . "/"]);

                $image->blog_id = $post->id;
                $image->external_url = $data['secure_url'];

                $image->save();

                $block->data->file->url = $image->url();
                $block->data->file->processed = true;
            }
        }
    }

    $post->content = json_encode($content);

    $post->save();

    $files = glob(__DIR__ . "/../../../files/images/temporary/*");

    foreach ($files as $file){
        if (is_file($file)) {
            unlink($file);
        }
    }

    BlogImage::whereNull("blog_id")->where("is_deleted", false)->delete();

    return [$post->id, $image_identifiers];
}

$redirect = "/blog.php";
$response_data = [];

switch($_POST['request']) {
    case "content":
        $response_data['content'] = Blog::find($_POST['id'])->content;

        break;
    case "create":
        $post_id = process_post_content()[0];
        $redirect = "/blog.php?edit=" . $post_id;

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
        $folder = "blog/" . $_POST['id'] . "/";

        $api->delete_resources_by_prefix($folder);

        Blog::find($_POST['id'])->delete();

        break;
    case "edit":
        $post = Blog::find($_POST['id']);
        $backup = Blog::create([
            "name" => $post->name,
            "type" => "Backup",
            "date" => $post->date,
            "author" => $post->author,
            "content" => $post->content,
            "backup_id" => $post->id
        ]);
        $used_images = process_post_content($post)[1];
        $all_images = BlogImage::where("blog_id", $post->id)->get("id")->pluck("id")->toArray();

        BlogImage::whereIn("id", array_diff($all_images, $used_images))->update([
            "is_deleted" => true,
            "blog_id" => null
        ]);

        break;
    case "createImage":
        $extension = strtolower($_POST['extension']);
        $image = BlogImage::create();
        $base_name = __DIR__ . "/../../../files/images/temporary/image-" . $image->id;
        $name = $base_name . "." . $extension;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $name)) {
            if ($extension !== "jpg") {
                $image_handle = imagecreatefrompng($name);
                $color = imagecreatetruecolor(imagesx($image_handle), imagesy($image_handle));

                imagefill($color, 0, 0, imagecolorallocate($color, 255, 255, 255));
                imagealphablending($color, true);
                imagecopy($color, $image_handle, 0, 0, 0, 0, imagesx($image_handle), imagesy($image_handle));
                imagedestroy($image_handle);
                imagejpeg($color, $base_name . ".jpg", 100);
                imagedestroy($color);
                unlink($name);
            }

            $response_data['url'] = $image->url();
            $response_data['id'] = $image->id;
        } else {
            $image->delete();
        }

        break;
    case "download":
        require(__DIR__ . "/../../Helpers/DownloadCSV.php");

        download_csv(Blog::class, BlogImage::class);

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
