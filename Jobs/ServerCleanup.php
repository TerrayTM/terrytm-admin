<?php

require_once(__DIR__ . "/../Partials/DatabaseConnector.php");

CronResult::all()->delete();

// Delete all image groups marked is_deleted = true

CronResult::create([
    "type" => CronType::$SERVER_CLEANUP,
    "is_successful" => true
]);

?>