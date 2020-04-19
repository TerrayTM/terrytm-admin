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
            <h1 class="h3 mb-2 text-gray-800">Manage Builds</h1>
          </div>
          <div class="card shadow mb-4" style="display: <?php echo(isset($_GET['log']) ? "none" : "block"); ?>;">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Builds</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Log</th>
                      <th>Duration</th>
                      <th>Success</th>
                      <th>Unit Tests</th>
                      <th>Setup</th>
                      <th>Twine</th>
                      <th>Timestamp</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                      if (!isset($_GET['log'])) {
                        $builds = Build::select([
                          "id",
                          "duration",
                          "is_successful",
                          "tests_passed",
                          "setup_passed",
                          "twine_passed",
                          "timestamp"
                        ])->orderBy("timestamp", "DESC")->limit(20)->get();

                        foreach ($builds as $build) {
                          echo('
                            <tr>
                              <td>' . $build->id . '</td>
                              <td><a href="/builds.php?log=' . $build->id . '">View Log</a></td>
                              <td>' . $build->duration . ' Seconds</td>
                              <td>' . ($build->is_successful ? "Passed" : "Failed") . '</td>
                              <td>' . ($build->tests_passed ? "Passed" : "Failed") . '</td>
                              <td>' . ($build->setup_passed ? "Passed" : "Failed") . '</td>
                              <td>' . ($build->twine_passed ? "Passed" : "Failed") . '</td>
                              <td>' . $build->timestamp . '</td>
                            </tr>
                          ');
                        }
                      }
                      
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="card shadow mb-4" style="display: <?php echo(isset($_GET['log']) ? "block" : "none"); ?>;">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">View Log</h6>
            </div>
            <div class="card-body">
              <div style="width: 100%; height: 600px; overflow: auto; border: 1px solid lightgray; border-radius: 3px; padding: 8px; cursor: default;" readonly>
                <?php 
                  
                  if (isset($_GET['log'])) {
                    echo(nl2br(Build::find($_GET['log'])->log)); 
                  }
                
                ?>
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