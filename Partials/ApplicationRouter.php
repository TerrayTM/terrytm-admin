<?php

// TODO: Referrer should be moved to front-end
// Group will be the referrer
// Use IP as new visitor indicator
if (isset($_GET['fbclid']) || isset($_GET['l']) || isset($_GET['r'])) {
    require_once(__DIR__ . "/DatabaseConnector.php");

    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $address = $_SERVER['HTTP_CLIENT_IP'];
    } else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if(isset($_SERVER['HTTP_X_FORWARDED'])) {
        $address = $_SERVER['HTTP_X_FORWARDED'];
    } else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $address = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if(isset($_SERVER['HTTP_FORWARDED'])) {
        $address = $_SERVER['HTTP_FORWARDED'];
    } else if(isset($_SERVER['REMOTE_ADDR'])) {
        $address = $_SERVER['REMOTE_ADDR'];
    }

    $referrer = "None";

    if (isset($_GET['fbclid'])) {
        $referrer = "Facebook";
    } else if (isset($_GET['l'])) {
        $referrer = "LinkedIn";
    } else if (isset($_GET['r'])) {
        $referrer = "Resume";
    }

    Analytics::create([
        "url" => "/",
        "group" => isset($_GET['fbclid']) ? "Facebook" : "Resume",
        "address" => $address,
        "is_error" => false
    ]);
}

$parts = explode("/", $_SERVER['REQUEST_URI']);

if (count($parts) === 3 && $parts[1] === "image-group") { 
    require_once(__DIR__ . "/../Resources/Components/ImageGallery.php");

    exit();
}

require_once(__DIR__ . "/TokenProvider.php")

?>
