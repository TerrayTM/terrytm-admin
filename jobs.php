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
            <h1 class="h3 mb-2 text-gray-800">Manage Jobs</h1>
            <a href="#" onClick="downloadTable()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Download Table</a>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Jobs</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Type</th>
                      <th>Last Execution</th>
                      <th>Duration</th>
                      <th>Average Duration</th>
                      <th>Success</th>
                      <th>Runs</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    $class = new ReflectionClass("CronType");
                    $static_members = $class->getStaticProperties();
                    $elements = [];
                    $messages = [];

                    foreach ($static_members as $field => $value) {
                      $result = CronResult::orderBy("timestamp", "DESC")->where("type", $value)->first();
                      $timestamp = "";
                      $duration = "";
                      $is_successful = true;
                      $success_rate = "";
                      $average_duration = "";
                      $runs = "";

                      if ($result) {
                        $timestamp = date("Y-m-d H:i:s", strtotime($result->timestamp . " UTC"));
                        $duration = $result->duration;
                        $is_successful = $result->is_successful;
                        $runs = CronResult::where("type", $value)->count();
                        $success_rate = round(CronResult::where("type", $value)->where("is_successful", true)->count() / $runs * 100) . "%";
                        $average_duration = number_format(CronResult::where("type", $value)->avg("duration"), 2, ".", "");
                        $message_groups = CronResult::selectRaw("message, type, COUNT(*) as count")->where("type", $value)->groupBy("message")->get()->filter(function ($value) {
                          return !is_null($value->message);
                        });

                        if ($message_groups->count() > 0) {
                          $messages[$value] = $message_groups;
                        }
                      }

                      $row = '
                        <tr' . ($is_successful ? '' : ' style="background-color: #eee;"') . '>
                          <td>' . $value . '</td>
                          <td>' . $timestamp . '</td>
                          <td>' . $duration . '</td>
                          <td>' . $average_duration . '</td>
                          <td>' . $success_rate . '</td>
                          <td>' . $runs . '</td>
                          <td class="center"><a href="#" onClick="deleteRow(event, \'' . $value . '\')"><span class="fa fa-trash"></span></a></td>
                        </tr>
                      ';

                      if ($result) {
                        array_unshift($elements , $row);
                      } else {
                        $elements[] = $row;
                      }
                    }

                    foreach ($elements as $element) {
                      echo($element);
                    }

                    ?>
                  </tbody>
                </table>
                <?php 

                if (count($static_members) === 0) {
                  echo('
                    <div class="card bg-success text-white shadow">
                      <div class="card-body">No jobs to display.</div>
                    </div>
                  ');
                }

                ?>
              </div>
            </div>
          </div>
          <?php

          if (count($messages) > 0) {
            echo('
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Messages</h6>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Type</th>
                          <th>Message</th>
                          <th>Count</th>
                        </tr>
                      </thead>
                      <tbody>
            ');

            foreach ($messages as $type => $groups) {
              foreach ($groups as $group) {
                echo('
                  <tr>
                    <td>' . $type . '</td>
                    <td>' . $group->message . '</td>
                    <td>' . $group->count . '</td>
                  </tr>
                ');
              }
            }

            echo('</tbody></table></div></div></div>');
          }

          ?>
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
    function deleteRow(event, type) {
      event.preventDefault();
      postRequest('/Controllers/Admin/Jobs.php', 'delete', { type });
    }

    function downloadTable() {
      postRequest('/Controllers/Admin/Jobs.php', 'download');
    }
  </script>
</body>
</html>
