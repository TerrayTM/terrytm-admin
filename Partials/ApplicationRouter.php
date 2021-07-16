<?php

$referrer = [
    "/r" => "Resume",
    "/l" => "LinkedIn",
    "/i" => "Instagram",
    "/g" => "Github"
];

if (array_key_exists($_SERVER['REQUEST_URI'], $referrer) || isset($_GET['fbclid'])) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (isset($_GET['fbclid'])) {
        $_SESSION['referrer'] = "Facebook";
    } else {
        $_SESSION['referrer'] = $referrer[$_SERVER['REQUEST_URI']];
    }

    header("Location: https://" . $_SERVER['HTTP_HOST']);

    exit();
}

$parts = explode("/", $_SERVER['REQUEST_URI']);

if (count($parts) === 3 && $parts[1] === "image-group") { 
    require_once(__DIR__ . "/../Resources/Components/ImageGallery.php");

    exit();
}

if (count($parts) === 6 && $parts[1] === "notification") { 
    require_once(__DIR__ . "/../Resources/Components/ManageNotification.php");

    exit();
}

require_once(__DIR__ . "/TokenProvider.php")

?>
