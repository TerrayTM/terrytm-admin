<?php

require_once(__DIR__ . "/../../Partials/RequestValidator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if (isset($_POST['request']) && $_POST['request'] === "logout") {
  if (isset($_COOKIE['autologin'])) {
    setcookie("autologin", "", time() - 3600, "/");
  }

  unset($_SESSION['authenticated']);
  header("Location: /login.php");

  exit();
} else {
  if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    header("Location: /dashboard.php");

    exit();
  } else {
    if (isset($_POST['username']) && isset($_POST['password'])) {
      require_once(__DIR__ . "/../../Config/Config.php");

      if ($_POST['username'] === config("username") && hash_equals(hash("sha256", $_POST['password']), config("password_hash"))) {
        // 2FA

        if (isset($_POST['remember']) && $_POST['remember']) {
          $payload = (string)(time() + 2592000);
          $signed_payload = json_encode([
            "expiry" => $payload,
            "signature" => password_hash(config("secret") . $payload . config("secret"), PASSWORD_DEFAULT)
          ]);

          setcookie("autologin", $signed_payload, time() + 2592000, "/");
        }

        $_SESSION['authenticated'] = true;

        SecurityLog::log(AdminEvent::$LOGIN_SUCCESS);

        header("Location: /dashboard.php");

        exit();
      } else {
        SecurityLog::log(AdminEvent::$LOGIN_FAILED);

        header("Location: /login.php?error=true");

        exit();
      }
    }
  }
}

?>