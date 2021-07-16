<?php

$styles = '
  <link href="/Resources/vendor/scheduler/dhtmlxscheduler_material.css" rel="stylesheet" type="text/css" charset="utf-8">
';

require_once(__DIR__ . "/Partials/Authenticator.php");
require_once(__DIR__ . "/Resources/Components/Header.php");

?>

<body id="page-top">
  <div id="wrapper">
    <?php require_once(__DIR__ . "/Resources/Components/SideBar.php"); ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?php require_once(__DIR__ . "/Resources/Components/HeadBar.php"); ?>
        <div class="container-fluid">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-2 text-gray-800">Manage Calendar</h1>
            <a href="#" onClick="downloadTable()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Download Table</a>
          </div>
          <div class="card bg-danger text-white shadow" style="margin-bottom: 24px; display: none;" id="errorMessage">
            <div class="card-body">Operation failed! Please try again later.</div>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary" id="title">Calendar (Loading...)</h6>
            </div>
            <div class="card-body">
              <div id="scheduler" class="dhx_cal_container" style="height: 800px;"> 
                <div class="dhx_cal_navline"> 
                  <div class="dhx_cal_prev_button">&nbsp;</div> 
                  <div class="dhx_cal_next_button">&nbsp;</div> 
                  <div class="dhx_cal_today_button"></div> 
                  <div class="dhx_cal_date"></div> 
                  <div class="dhx_cal_tab" name="day_tab"></div> 
                  <div class="dhx_cal_tab" name="week_tab"></div> 
                  <div class="dhx_cal_tab" name="month_tab"></div> 
                </div> 
                <div class="dhx_cal_header"></div> 
                <div class="dhx_cal_data"></div> 
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
  <?php require_once(__DIR__ . "/Resources/Components/Calendar.php"); ?>
  <script>
    function downloadTable() {
      postRequest('/Controllers/Admin/Calendar.php', 'download');
    }
  </script>
</body>
</html>