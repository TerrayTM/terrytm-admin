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
            <h1 class="h3 mb-2 text-gray-800">Manage Analytics</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Download Table</a>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Analytics</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>URL Path</th>
                      <th>Total Count</th>
                      <th>Unique Count</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                      $analytics = Analytics::all()->groupBy("url")->sort()->reverse();

                      foreach ($analytics as $url => $group) {
                        echo('
                          <tr' . ($group[0]->is_error ? ' style="background-color: #eee;"' : '') .'>
                            <td>' . $url . '</td>
                            <td>' . $group->count() . '</td>
                            <td>' . $group->groupBy("address")->count() . '</td>
                            <td class="center"><a href="#" onClick="deleteRow(event, \'' . $url . '\')"><span class="fa fa-trash"></span></a></td>
                          </tr>
                        ');
                      }

                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Breakdown</h6>
            </div>
            <div class="card-body">
              <p>Visits from Facebook: <?php echo(Analytics::where("group", "Facebook")->count()); ?></p>
              <p>Visits from Resume: <?php echo(Analytics::where("group", "Resume")->count()); ?></p>
              <p>Visits from LinkedIn: <?php echo(Analytics::where("group", "LinkedIn")->count()); ?></p>
              <p>Visits from Self: <?php echo(Analytics::where("is_self", true)->count()); ?></p>
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
    <script>
      function deleteRow(event, url) {
        event.preventDefault();
        postRequest('/Controllers/Admin/Analytics.php', 'delete', { url });
      }
    </script>
</body>
</html>