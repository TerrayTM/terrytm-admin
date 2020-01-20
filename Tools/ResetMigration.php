<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!isset($_SERVER['REQUEST_METHOD'])) {
    if ($argc === 2) {
        $migration = $argv[1];
        $path = __DIR__ . "/../Migrations/" . $migration . ".php";

        if (is_file($path)) {
            require_once(__DIR__ . "/../Partials/DatabaseConnector.php");
            require_once($path);

            $migrationClass = new $migration(Capsule::class);

            $migrationClass->down();
            
            echo($migration . " migration reset successfully.\n");
        } else {
            echo("Invalid migration name.");
        }
    } else {
        echo("Migration name missing.");
    }
}

?>