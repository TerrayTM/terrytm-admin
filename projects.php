<?php

require_once(__DIR__ . "/Partials/Authenticator.php");
require_once(__DIR__ . "/Resources/Components/Header.php");
require_once(__DIR__ . "/Partials/DatabaseConnector.php");

?>

<body id="page-top">
  <div id="wrapper">
    <?php require_once(__DIR__ . "/Resources/Components/SideBar.php"); ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?php require_once(__DIR__ . "/Resources/Components/HeadBar.php"); ?>
        <div class="container-fluid">
          <?php
            
            if (isset($_GET['edit']) || isset($_GET['create'])) {
              require_once(__DIR__ . "/Resources/Components/UpdateProject.php");
            } else {
              require_once(__DIR__ . "/Resources/Components/ProjectsTable.php");
            }

          ?>
        </div>
      </div>
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Terry™ 2019</span>
          </div>
        </div>
      </footer>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>
    <?php require_once(__DIR__ . "/Resources/Components/Footer.php"); ?>
    <script>
      function deleteRow(event, id) {
        event.preventDefault();
        postRequest('/Controllers/Admin/Projects.php', 'delete', { id });
      }
    </script>
</body>
</html>