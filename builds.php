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
            <a href="#" onClick="downloadTable()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Download Table</a>
          </div>
          <?php

          if (isset($_GET['success'])) {
            echo('
              <div class="mb-4">
                <div class="card bg-info text-white shadow">
                  <div class="card-body">' . ($_GET['success'] === "true" ? 'Rebuild request has been queued.' : 'An error has occurred.') . '</div>
                </div>
              </div>
            ');
          }

          ?>
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
                      <th>Repository</th>
                      <th>Log</th>
                      <th>Duration</th>
                      <th>Success</th>
                      <th>Unit Tests</th>
                      <th>Setup</th>
                      <th>Twine</th>
                      <th>Timestamp</th>
                      <th>Retry</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    if (!isset($_GET['log'])) {
                      $builds = Build::orderBy("timestamp", "DESC")->limit(20)->get();

                      foreach ($builds as $build) {
                        $push = $build->find_parent();
                        $repository = "??????";

                        if ($push) {
                          $repository = $push->repository;
                        }

                        echo('
                          <tr' . ($build->is_successful ? '' : ' style="background-color: #eee;" ') . '>
                            <td>' . $build->id . '</td>
                            <td>' . $repository . '</td>
                            <td><a href="/builds.php?log=' . $build->id . '">View Log</a></td>
                            <td>' . $build->duration . ' Seconds</td>
                            <td>' . ($build->is_successful ? "Passed" : "Failed") . '</td>
                            <td>' . ($build->tests_passed ? "Passed" : "Failed") . '</td>
                            <td>' . ($build->setup_passed ? "Passed" : "Failed") . '</td>
                            <td>' . ($build->twine_passed ? "Passed" : "Failed") . '</td>
                            <td>' . date("Y-m-d H:i:s", strtotime($build->timestamp . " UTC")) . '</td>
                            <td class="center"><a href="#" onClick="retry(event, \'' . $build->id . '\')"><span class="fa fa-redo"></span></a></td>
                          </tr>
                        ');
                      }
                    }

                    ?>
                  </tbody>
                </table>
                <?php

                if (!isset($_GET['log']) && $builds->count() === 0) {
                  echo('
                    <div class="card bg-success text-white shadow">
                      <div class="card-body">No builds to display.</div>
                    </div>
                  ');
                }

                ?>
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
      <?php require_once(__DIR__ . "/Resources/Components/Footer.php"); ?>
    </div>
  </div>
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <?php require_once(__DIR__ . "/Resources/Components/Scripts.php"); ?>
  <script>
    function downloadTable() {
      postRequest('/Controllers/Admin/Builds.php', 'download');
    }

    function retry(event, id) {
      event.preventDefault();
      postRequest('/Controllers/Admin/Builds.php', 'retry', { id });
    }
  </script>
</body>
</html>