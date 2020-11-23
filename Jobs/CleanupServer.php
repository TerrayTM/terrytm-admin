<?php

require_once(__DIR__ . "/../Partials/DatabaseConnector.php");

CronResult::truncate();
Token::truncate();

foreach (Image::where("is_deleted", true)->get() as $image) {
    $name = __DIR__ . "/../../files/images/" . $image->group_id . "/" . $image->name;

    if (!unlink($name)) {
        CronResult::create([
            "type" => CronType::$CleanupServer,
            "is_successful" => false,
            "message" => "Failed to delete image '" . $name . "'."
        ]);

        return; 
    }

    $image->delete();
}

foreach (ImageGroup::where("is_deleted", true)->get() as $group) {
    $name = __DIR__ . "/../../files/images/" . $group->id;

    if (!rmdir($name)) {
        CronResult::create([
            "type" => CronType::$CleanupServer,
            "is_successful" => false,
            "message" => "Failed to delete group '" . $name . "'."
        ]);

        return; 
    }

    $group->delete();
}

CronResult::create([
    "type" => CronType::$CleanupServer,
    "is_successful" => true
]);

?>