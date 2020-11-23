<?php

require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$time = date("H:i:s", time());
$messageCount = Message::where("has_seen", false)->count();

if ($messageCount === 0) {
  $messageCount = null;
}

?>

<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
  <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
  </button>
  <p style="margin: 0;">
    <span id="version">
      <span class="fa fa-code-branch"></span> V1.3.0
    </span>
    <span id="clock">
      <span class="fa fa-clock"></span> <?php echo($time); ?>
    </span>
  </p>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item mx-1">
      <a class="nav-link" target="_blank" href="https://auth-db106.hostinger.com">
        <i class="fas fa-database fa-fw"></i>
      </a>
    </li>
    <li class="nav-item mx-1">
      <a class="nav-link" target="_blank" href="https://webmail1.hostinger.com" onClick="deleteMessages()">
        <i class="fas fa-envelope fa-fw"></i>
        <span class="badge badge-danger badge-counter"><?php echo($messageCount); ?></span>
      </a>
    </li>
    <div class="topbar-divider d-none d-sm-block"></div>
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="mr-2 d-none d-lg-inline text-gray-600 small">Terry Zheng</span>
        <img class="img-profile rounded-circle" src="/Resources/Images/Profile.png">
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="#" onClick="logout()">
          <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
          Logout
        </a>
      </div>
    </li>
  </ul>
</nav>
