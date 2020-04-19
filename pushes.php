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
            <h1 class="h3 mb-2 text-gray-800">Manage Pushes</h1>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Pushes</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Repository</th>
                      <th>Url</th>
                      <th>User</th>
                      <th>Email</th>
                      <th>Timestamp</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                      $pushes = Push::orderBy("created_at", "DESC")->limit(20)->get();

                      foreach ($pushes as $push) {
                        echo('
                          <tr>
                            <td>' . $push->id . '</td>
                            <td>' . $push->repository . '</td>
                            <td>' . $push->url . '</td>
                            <td>' . $push->user . '</td>
                            <td>' . ($push->email ?? "None") . '</td>
                            <td>' . $push->created_at . '</td>       
                            <td>' . $push->status() . '</td>
                          </tr>
                        ');
                      }
                      
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
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
</body>
</html>