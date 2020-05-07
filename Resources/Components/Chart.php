<?php

use Carbon\Carbon;

require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");
require_once(__DIR__ . "/../../Helpers/GetAddress.php");

$address = get_address(true) ?? -1;

Analytics::where("is_self", false)->where("address", $address)->update([
  "is_self" => true
]);

$analytics = Analytics::whereDate("timestamp", ">=", Carbon::now()->startOfMonth())->get()->groupBy(function ($item) {
  return Carbon::parse($item->timestamp)->format("d");
});

$days_in_month = date("t");
$views = array_fill(0, $days_in_month, 0);
$unique_views = array_fill(0, $days_in_month, 0);
$errors = array_fill(0, $days_in_month, 0);
$counts = array_fill(0, $days_in_month, 0);

for ($i = 1; $i <= $days_in_month; ++$i) {
  $labels[] = $i;
}

foreach ($analytics as $day => $group) {
  $views[$day - 1] = $group->count();
  $errors[$day - 1] = $group->where("is_error", true)->count();
  $unique_views[$day - 1] = $group->groupBy("address")->count();
  $counts[$day - 1] = $group->where("is_self", true)->count();
}

$labels = implode(",", $labels);
$views = implode(",", $views);
$unique_views = implode(",", $unique_views);
$errors = implode(",", $errors);
$counts = implode(",", $counts);

?>

<script src="/Resources/vendor/chart.js/chart.min.js"></script>
<script>
  const currentAddress = '<?php echo($address); ?>';
  Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
  Chart.defaults.global.defaultFontColor = '#858796';
  const analyticsChart = new Chart(document.getElementById('chart'), {
    type: 'line',
    data: {
      labels: [<?php echo($labels); ?>],
      datasets: [
        {
          label: 'Users',
          lineTension: 0.3,
          backgroundColor: 'rgba(78, 115, 223, 0.05)',
          borderColor: 'rgba(101, 220, 212, 1)',
          pointRadius: 3,
          pointBackgroundColor: 'rgba(101, 182, 220, 1)',
          pointBorderColor: 'rgba(101, 220, 212, 1)',
          pointHoverRadius: 3,
          pointHoverBackgroundColor: 'rgba(101, 220, 212, 1)',
          pointHoverBorderColor: 'rgba(101, 220, 212, 1)',
          pointHitRadius: 10,
          pointBorderWidth: 2,
          data: [<?php echo($unique_views); ?>]
        },
        {
          label: 'Views',
          lineTension: 0.3,
          backgroundColor: 'rgba(78, 115, 223, 0.05)',
          borderColor: 'rgba(78, 115, 223, 1)',
          pointRadius: 3,
          pointBackgroundColor: 'rgba(78, 115, 223, 1)',
          pointBorderColor: 'rgba(78, 115, 223, 1)',
          pointHoverRadius: 3,
          pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
          pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
          pointHitRadius: 10,
          pointBorderWidth: 2,
          data: [<?php echo($views); ?>]
        },
        {
          label: 'Counts',
          lineTension: 0.3,
          backgroundColor: 'rgba(78, 115, 223, 0.05)',
          borderColor: 'lightgreen',
          pointRadius: 3,
          pointBackgroundColor: 'lightgreen',
          pointBorderColor: 'lightgreen',
          pointHoverRadius: 3,
          pointHoverBackgroundColor: 'lightgreen',
          pointHoverBorderColor: 'lightgreen',
          pointHitRadius: 10,
          pointBorderWidth: 2,
          data: [<?php echo($counts); ?>]
        },
        {
          label: 'Errors',
          lineTension: 0.3,
          backgroundColor: 'rgba(78, 115, 223, 0.05)',
          borderColor: 'red',
          pointRadius: 3,
          pointBackgroundColor: 'red',
          pointBorderColor: 'red',
          pointHoverRadius: 3,
          pointHoverBackgroundColor: 'red',
          pointHoverBorderColor: 'red',
          pointHitRadius: 10,
          pointBorderWidth: 2,
          data: [<?php echo($errors); ?>]
        }
      ],
    },
    options: {
      maintainAspectRatio: false,
      layout: {
        padding: {
          left: 10,
          right: 25,
          top: 25,
          bottom: 0
        }
      },
      scales: {
        xAxes: [
          {
            time: {
              unit: 'date'
            },
            gridLines: {
              display: false,
              drawBorder: false
            },
            ticks: {
              maxTicksLimit: 10
            }
          }
        ],
        yAxes: [
          {
            ticks: {
              maxTicksLimit: 5,
              padding: 10,
              beginAtZero: true
            },
            gridLines: {
              color: 'rgb(234, 236, 244)',
              zeroLineColor: 'rgb(234, 236, 244)',
              drawBorder: false,
              borderDash: [2],
              zeroLineBorderDash: [2]
            }
          }
        ],
      },
      legend: {
        display: false
      },
      tooltips: {
        backgroundColor: 'rgb(255,255,255)',
        bodyFontColor: '#858796',
        titleMarginBottom: 10,
        titleFontColor: '#6e707e',
        titleFontSize: 14,
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        intersect: false,
        mode: 'index',
        caretPadding: 10
      }
    }
  });
</script>
