<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$user_authenticated = false;

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
    $user_authenticated = true;
} else {
    if (isset($_COOKIE['autologin'])) {
        try {
            $decoded = json_decode($_COOKIE['autologin']);

            if (isset($decoded->expiry) && isset($decoded->signature)) {
                require_once(__DIR__ . "/../Config/Config.php");

                if ((int)$decoded->expiry > time() && password_verify(config("secret") . $decoded->expiry . config("secret"), $decoded->signature)) {
                    $user_authenticated = true;
                    $_SESSION['authenticated'] = true;
                } else {
                    setcookie("autologin", "", time() - 3600, "/");
                }
            } else {
                setcookie("autologin", "", time() - 3600, "/");
            }
        } catch (Exception $exception) {
            setcookie("autologin", "", time() - 3600, "/");
        }
    }
}

if (!$user_authenticated && !isset($optional_authentication)) {
    header("Location: /login.php");

    exit();
}

?>