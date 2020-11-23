<?php

class AppLocation {
    public static $error_logs = [
        __DIR__ . "/../../error_log", 
        __DIR__ . "/../error_log",
        __DIR__ . "/../Controllers/Admin/error_log",
        __DIR__ . "/../Controllers/Services/error_log",
        __DIR__ . "/../Controllers/Public/error_log"
    ];
}

?>
