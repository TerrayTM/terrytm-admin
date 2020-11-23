<?php

$styles = '
  <link href="/Resources/vendor/jquery-ui/jquery-ui.min.css" rel="stylesheet">
  <style>
    .tag {
      cursor: pointer;
      margin: 6px;
      font-size: 16px;
      box-shadow: none !important;
      border: none !important;
    }

    .tag span {
      margin-left: 12px;
      pointer-events: none;
    }

    .technology-container,
    .tag-container {
      border: 1px solid #d1d3e2;
      border-radius: .35rem;
    }

    #image-container {
      list-style-type: none;
      margin: 0;
      padding: 8px;
      border-radius: .35rem;
      border: 1px solid #d1d3e2;
      display: none;
    }

    #image-container:after {
      content: "";
      display: table;
      clear: both;
    }

    #image-container li {
      margin: 4px;
      float: left;
      width: 272px;
      position: relative;
    }

    #image-container button {
      position: absolute;
      right: 4px;
      top: 4px;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      border: 1px solid white;
      font-weight: bold;
      background-color: black;
      color: white;
      outline: none;
    }
  </style>
';

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
      <?php require_once(__DIR__ . "/Resources/Components/Footer.php"); ?>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>
    <?php require_once(__DIR__ . "/Resources/Components/Scripts.php"); ?>
    <script src="/Resources/vendor/jquery-ui/jquery-ui.min.js"></script>
    <script>
      function downloadTable() {
        postRequest('/Controllers/Admin/Projects.php', 'download');
      }

      function deleteRow(event, id) {
        event.preventDefault();
        postRequest('/Controllers/Admin/Projects.php', 'delete', { id });
      }
    </script>
    <?php

    if (isset($_GET['edit'])) {
      require_once(__DIR__ . "/Resources/Components/UpdateScript.php");
    }

    ?>
</body>
</html>