<?php

require_once(__DIR__ . "/../Config/Config.php");
require_once(__DIR__ . "/../vendor/autoload.php");

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    "driver" => "mysql",
    "host" => config("database_host"),
    "database" =>  config("database_name"),
    "username" => config("database_user"),
    "password" => config("database_password"),
    "charset" => "utf8",
    "collation" => "utf8_unicode_ci"
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

?>