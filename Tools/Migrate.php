<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!isset($_SERVER['REQUEST_METHOD'])) {
    require_once(__DIR__ . "/../Partials/DatabaseConnector.php");

    $migrated_classes = [];
    $deferred_migrations = [];
    $skipped = 0;

    foreach (scandir(__DIR__ . "/../Migrations") as $migration) {
        $path = __DIR__ . "/../Migrations/" . $migration;

        if (is_file($path)) {
            require_once($path);

            $migration = explode(".", $migration)[0];

            if (isset($migration::$required_migration) && !in_array($migration::$required_migration, $migrated_classes)) {
                $deferred_migrations[] = $migration;

                continue;
            }

            $migrationClass = new $migration(Capsule::class);

            if ($migrationClass->up()) {
                echo($migration . " migrated successfully.\n");
            } else {
                ++$skipped;
            }
            
            $migrated_classes[] = $migration;
        }
    }

    $counter = 0;

    foreach ($deferred_migrations as $migration) {
        if (in_array($migration::$required_migration, $migrated_classes)) {
            $migrationClass = new $migration(Capsule::class);

            if ($migrationClass->up()) {
                echo($migration . " migrated successfully.\n");
            } else {
                ++$skipped;
            }

            ++$counter;
        }
    }

    if ($counter !== count($deferred_migrations)) {
        echo("An error has occurred.");
    }

    echo("\nMigration complete!\n");
    
    if ($skipped > 0) {
        echo("Skipped: " . $skipped);
    }
}

?>