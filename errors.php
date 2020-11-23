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
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-2 text-gray-800">Manage Errors</h1>
            <a href="#" onClick="downloadTable()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Download Table</a>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Errors</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>JSON</th>
                      <th>Timestamp</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    $errors = AppError::orderBy("id")->get();

                    foreach ($errors as $error) {
                      echo('
                        <tr>
                          <td>' . $error->id . '</td>
                          <td>' . $error->json . '</td>
                          <td style="white-space: pre;">' . $error->timestamp . '</td>
                          <td class="center"><a href="#" onClick="deleteRow(event, \'' . $error->id . '\')"><span class="fa fa-trash"></span></a></td>
                        </tr>
                      ');
                    }

                    ?>
                  </tbody>
                </table>
                <?php 
                
                if ($errors->count() === 0) {
                  echo('
                    <div class="card bg-success text-white shadow">
                      <div class="card-body">No errors were detected. Congrats!</div>
                    </div>
                  ');
                }
                
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php require_once(__DIR__ . "/Resources/Components/Footer.php"); ?>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>
    <?php require_once(__DIR__ . "/Resources/Components/Scripts.php"); ?>
    <script>
      function deleteRow(event, id) {
        event.preventDefault();
        postRequest('/Controllers/Admin/Errors.php', 'delete', { id });
      }

      function downloadTable() {
        postRequest('/Controllers/Admin/Errors.php', 'download');
      }
    </script>
</body>
</html>