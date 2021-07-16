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
            <h1 class="h3 mb-2 text-gray-800">Manage Requests</h1>
            <a href="#" onClick="downloadTable()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Download Table</a>
          </div>
          <?php

          if (isset($_GET['error'])) {
            echo('
              <div class="card bg-danger text-white shadow" style="margin-bottom: 24px;">
                <div class="card-body">Invalid request parameters.</div>
              </div>
            ');
          }

          ?>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Requests</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>URL</th>
                      <th>Body</th>
                      <th>Timestamp</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    $requests = Request::all();

                    foreach ($requests as $request) {
                      echo('
                        <tr' . ($request->is_successful ? '' : ' style="background-color: #eee;"') . '>
                          <td>' . $request->url . '</td>
                          <td>' . $request->json . '</td>
                          <td style="white-space: pre;">' . date("Y-m-d H:i:s", strtotime($request->updated_at)) . '</td>
                          <td class="center"><a href="#" onClick="deleteRow(event, \'' . $request->id . '\')"><span class="fa fa-trash"></span></a></td>
                        </tr>
                      ');
                    }
                      
                    ?>
                    <tr>
                      <td contenteditable id="url"></td>
                      <td contenteditable id="body"></td>
                      <td></td>
                      <td class="center"><a href="#" onClick="create(event)"><span class="fa fa-save"></span></a></td>
                    </tr>
                  </tbody>
                </table>
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
    const preventFormat = (event) => {
      event.preventDefault();
      const text = (event.originalEvent || event).clipboardData.getData('text/plain');
      document.execCommand('insertHTML', false, text);
    };

    document.getElementById('url').addEventListener('paste', preventFormat);
    document.getElementById('body').addEventListener('paste', preventFormat);

    function create(event) {
      event.preventDefault();
      document.getElementById('body').style.backgroundColor = 'initial';
      const url = document.getElementById('url').innerText.trim();
      const body = document.getElementById('body').innerText.trim();
      if (!url || !body) {
        return;
      }
      try {
        JSON.parse(body);        
        postRequest('/Controllers/Admin/Requests.php', 'create', { url, body });
      } catch (exception) {
        document.getElementById('body').style.backgroundColor = '#eee';
      }
    }

    function deleteRow(event, id) {
      event.preventDefault();
      postRequest('/Controllers/Admin/Requests.php', 'delete', { id });
    }

    function downloadTable() {
      postRequest('/Controllers/Admin/Requests.php', 'download');
    }
  </script>
</body>
</html>
