<?php

$parts = explode("/", $_SERVER['REQUEST_URI']);

if (count($parts) === 3 && $parts[1] === "image-group") { 
    require_once(__DIR__ . "/../Resources/Components/ImageGallery.php");

    exit();
}

require_once(__DIR__ . "/TokenProvider.php")

?>
