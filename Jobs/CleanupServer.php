<?php

$start_time = microtime(true);

require_once(__DIR__ . "/../Partials/DatabaseConnector.php");
require_once(__DIR__ . "/../Helpers/WithReconnect.php");

$last_week = date("Y-m-d H:i:s", time() - 604800);

CronResult::where("timestamp", "<=", $last_week)->delete();
Token::where("created_at", "<=", $last_week)->delete();

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

// To do: Delete blog images
// Remove is_deleted=true blog images and remove from CDN

with_reconnect(function () use ($start_time) {
    CronResult::create([
        "type" => CronType::$CleanupServer,
        "is_successful" => true,
        "duration" => microtime(true) - $start_time
    ]);
});

?>
