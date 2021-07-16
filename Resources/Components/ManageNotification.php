<?php

require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");
require_once(__DIR__ . "/../../Config/Config.php");

$parts = explode("/", $_SERVER['REQUEST_URI']);
$notification = null;

if (count($parts) === 6 && $parts[1] === "notification") {
  $candidate = config("secret") . json_encode(array_slice($parts, 0, 5)) . config("secret");

  if (password_verify($candidate, rawurldecode($parts[5]))) {
    if (intval($parts[3]) > time() && Token::if_valid_then_consume($parts[2])) {
      $notification = CalendarNotification::find($parts[4]);
    }
  }
}

if (!$notification) {
  header("Location: https://terrytm.com");

  exit();
}

$notification->update(["should_notify" => false]);

?>

<html>
  <head>
    <title>Terry&trade;</title>
    <link rel="shortcut icon" href="https://terrytm.com/favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Terry Zheng">
    <style>
      html {
        height: 100%;
      }

      body {
        padding: 0;
        margin: 0;
        width: 100%;
        height: 100%;
        background-color: cornflowerblue;
        font: normal 14px/1.618em "Roboto", sans-serif;
        display: flex;
      }

      section {
        width: 80%;
        max-width: 360px;
        margin: auto;
        background-color: aliceblue;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        transition: box-shadow 0.3s;
      }

      section:hover {
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
      }

      h1, h4 {
        padding: 0;
        margin: 0;
      }

      .header {
        padding: 16px;
        background-color: gold;
      }

      .body {
        padding: 32px 16px;
      }

      a {
        display: block;
        text-align: center;
        text-decoration: none;
        background-color: gold;
        padding: 8px;
        margin-top: 32px;
      }

      a:active {
        color: blue;
      }
    </style>
  </head>
  <body>
    <section>
      <div class="header">
        <h1>&starf; Terry&trade; API</h1>
      </div>
      <div class="body">
        <h4>You have been unsubscribed from reminders.</h4>
        <a href="https://terrytm.com">Visit Homepage</a>
      </div>
    </section>
  </body>
</html>
