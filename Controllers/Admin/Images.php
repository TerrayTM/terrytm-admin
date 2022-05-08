<?php

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

function generate_link_id() {
    require_once(__DIR__ . "/../../Helpers/GenerateToken.php");

    $candidate = generate_token(32);
    $timeout = 20;

    while (ImageGroup::where("link_id", $candidate)->first()) {
        $candidate = generate_token(32);
        
        --$timeout;

        if ($timeout <= 0) {
            header("Location: /images.php");

            exit();
        }
    }

    return $candidate;
}

$redirect = "/images.php";
$response_data = [];

switch($_POST['request']) {
    case "restore":
        $group = ImageGroup::find($_POST['id']);

        $group->unset_delete();

        break;
    case "delete":
        $group = ImageGroup::find($_POST['id']);

        $group->set_delete();
        
        break;
    case "create":
        $group = ImageGroup::create([
            "name" => "Folder",
            "date" => date("Y-m-d"),
            "link_id" => generate_link_id()
        ]);
        $name = __DIR__ . "/../../../files/images/" . $group->id;

        mkdir($name);

        if (!file_exists($name)) {
            $group->delete();
            
            $redirect = "/images.php?error=true";
        }

        break;
    case "upload":
        require_once(__DIR__ . "/../../Helpers/GenerateToken.php");
        
        if (is_numeric($_POST['id'])) {
            for ($i = 0; $i < count($_FILES['images']['name']); ++$i) {
                $type = explode("/", $_FILES['images']['type'][$i])[1];
                $file_name = str_replace(".", "", uniqid("image-", true)) . generate_token(9) . "." . $type;
                $name = __DIR__ . "/../../../files/images/" . $_POST['id'] . "/" . $file_name;

                move_uploaded_file($_FILES['images']['tmp_name'][$i], $name);

                if (file_exists($name)) {
                    Image::create([
                        "name" => $file_name,
                        "group_id" => $_POST['id'],
                        "size" => $_FILES['images']['size'][$i]
                    ]);
                } else {
                    $redirect = "/images.php?error=true";
                }
            }
        } else {
            AppError::create([
                "json" => json_encode([
                    "operation" => "create",
                    "controller" => "images",
                    "id" => $_POST['id']
                ])
            ]);
        }
        
        break;
    case "refresh":
        ImageGroup::find($_POST['id'])->update([
            "link_id" => generate_link_id()
        ]);

        break;
    case "rename":
        ImageGroup::find($_POST['id'])->update([
            "name" => $_POST['name']
        ]);

        break;
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
