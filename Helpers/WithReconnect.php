<?php
        
require_once(__DIR__ . "/../Partials/DatabaseConnector.php");

use Illuminate\Database\Capsule\Manager as Capsule;

if (!function_exists("with_reconnect")) {
    function with_reconnect($action) {
        set_error_handler(function () use ($action) {
            Capsule::connection("default")->disconnect();
            Capsule::connection("default")->reconnect();
            $action();
        });
        $action();
        restore_error_handler();
    }
}

?>
