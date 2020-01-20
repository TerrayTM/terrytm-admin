<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['token']) || !$_SESSION['token']) {
    require_once(__DIR__ . "/../Helpers/GenerateToken.php");

    $_SESSION['token'] = generate_token();
}

$token = $_SESSION['token'];
$token_input = '<input type="hidden" name="token" value="' . $token . '">';

?>