<?php

require_once(__DIR__ . "/Partials/Authenticator.php");
require_once(__DIR__ . "/Resources/Components/Header.php");
require_once(__DIR__ . "/Partials/DatabaseConnector.php");

$current_time = $_GET['time'] ?? date("Y-m");
$current_day = $_GET['day'] ?? null;
$override_range = $_GET['time'] ?? "Y-m";
$analytics = null;

if ($current_day) {
  $selected_day = $current_time . "-" . $current_day . " 00:00:00";
  $next_day = date("Y-m-d H:i:s", strtotime($selected_day . " next day"));
  $analytics = Analytics::where("timestamp", ">=", gmdate("Y-m-d H:i:s", strtotime($selected_day)))->where("timestamp", "<", gmdate("Y-m-d H:i:s", strtotime($next_day)))->get();
} else {
  $analytics = Analytics::select("url", "is_error", "group", "referrer")->get();
}

$referrer_sources = $analytics->unique("referrer")->pluck("referrer")->toArray();
$referrer_strings = [];
$referrer_data = [];

foreach ($referrer_sources as $source) {
  $referrer_data[] = $analytics->where("referrer", $source)->count();
  $referrer_strings[] = "'" . $source . "'";
}

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
            <a href="#" onClick="downloadTable()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Download Table</a>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Analytics <?php echo(date("(F, Y)", strtotime($current_time))); ?></h6>
              <div class="input-group" style="width: 66px;">
                <a href="/analytics.php?time=<?php echo(date("Y-m", strtotime($current_time . " last month"))); ?>" class="form-control" style="text-decoration: none;">&lt;</a>
                <a href="/analytics.php?time=<?php echo(date("Y-m", strtotime($current_time . " next month"))); ?>" class="form-control" style="text-decoration: none;">&gt;</a>
              </div>
            </div>
            <div class="card-body">
              <div class="chart-area">
                <canvas id="chart"></canvas>
              </div>
            </div>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Details (<?php echo($current_day ? htmlspecialchars($current_time) . "-" . htmlspecialchars($current_day) : "Summary") ?>)</h6>
              <?php echo($current_day ? '<a href="/analytics.php?time=' . htmlspecialchars($current_time) . '">View Summary</a>' : '') ?>
            </div>
            <div class="card-body">
              <canvas id="referrerChart" style="max-width: 800px; margin: auto auto 42px auto; display: <?php echo($analytics->count() === 0 ? "none" : "block"); ?>;"></canvas>
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <?php

                      if ($current_day) {
                        echo("
                          <th>URL Path</th>
                          <th>Group</th>
                          <th>IP</th>
                          <th>Referrer</th>
                          <th>Timestamp</th>
                          <th>Delete</th>
                        ");
                      } else {
                        echo("
                          <th>URL Path</th>
                          <th>Total Count</th>
                          <th>Unique Count</th>
                          <th>Delete</th>
                        ");
                      }

                      ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    if ($current_day) {
                      $group_alias = [];
                      $group_id = 1;

                      foreach ($analytics->sortBy(function ($entry) { return strtotime($entry->timestamp); }) as $item) {
                        $group = 0;

                        if (isset($group_alias[$item->group])) {
                          $group = $group_alias[$item->group];
                        } else {
                          $group = $group_id++;
                          $group_alias[$item->group] = $group;
                        }

                        echo('
                          <tr' . ($item->is_error ? ' style="background-color: #eee;"' : '') . '>
                            <td>' . $item->url . '</td>
                            <td>' . $group . '</td>
                            <td>' . $item->address . '</td>
                            <td>' . $item->referrer . '</td>
                            <td>' . date("h:iA", strtotime($item->timestamp . "UTC")) . '</td>
                            <td class="center"><a href="#" onClick="deleteItem(event, \'' . $item->id . '\')"><span class="fa fa-trash"></span></a></td>
                          </tr>
                        ');
                      }
                    } else {
                      foreach ($analytics->groupBy("url")->sort()->reverse() as $url => $group) {
                        echo('
                          <tr' . ($group[0]->is_error ? ' style="background-color: #eee;"' : '') . '>
                            <td>' . $url . '</td>
                            <td>' . $group->count() . '</td>
                            <td>' . $group->groupBy("group")->count() . '</td>
                            <td class="center"><a href="#" onClick="deleteRow(event, \'' . $url . '\')"><span class="fa fa-trash"></span></a></td>
                          </tr>
                        ');
                      }
                    }

                    ?>
                  </tbody>
                </table>
                <?php 

                if ($analytics->count() === 0) {
                  echo('
                    <div class="card bg-success text-white shadow">
                      <div class="card-body">No analytics to display.</div>
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
  </div>
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <?php require_once(__DIR__ . "/Resources/Components/Scripts.php"); ?>
  <?php require_once(__DIR__ . "/Resources/Components/Chart.php"); ?>
  <script>
    const labels = [<?php echo(implode(", ", $referrer_strings)); ?>];
    const colors = labels.map((i) =>  i.split('').reduce((a, _, index) => a + i.charCodeAt(index) * (index * index + 1), 0));
    const formattedColors = colors.map((i) => '#' + Math.floor((Math.abs(Math.sin(i) * 16777215)) % 16777215).toString(16));

    document.getElementById('chart').addEventListener('click', (event) => {
      const points = analyticsChart.getElementsAtEvent(event);
      if (points.length > 0) {
        const day = points[0]._index + 1;
        window.location.href = `/analytics.php?time=<?php echo(htmlspecialchars($current_time)); ?>&day=${day.toString().padStart(2, '0')}`;
      }
    });

    function deleteRow(event, url) {
      event.preventDefault();
      postRequest('/Controllers/Admin/Analytics.php', 'delete', { url, referrer: `${window.location.pathname}${window.location.search}` });
    }

    function deleteItem(event, id) {
      event.preventDefault();
      postRequest('/Controllers/Admin/Analytics.php', 'deleteItem', { id, referrer: `${window.location.pathname}${window.location.search}` });
    }

    function downloadTable() {
      postRequest('/Controllers/Admin/Analytics.php', 'download');
    }

    const referrerChart = new Chart(document.getElementById('referrerChart'), {
      type: 'doughnut',
      data: {
        labels,
        datasets: [
          {
            label: 'Referrer',
            data: [<?php echo(implode(", ", $referrer_data)); ?>],
            backgroundColor: formattedColors,
            hoverOffset: 4
          }
        ]
      }
    });
  </script>
</body>
</html>
