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
            <h1 class="h3 mb-2 text-gray-800">Manage SSL</h1>
            <a href="#" onClick="downloadTable()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Download Table</a>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">SSL Watch List</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>URL</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    $watch_items = SSL::all();

                    foreach ($watch_items as $item) {
                      echo('
                        <tr' . ($item->is_valid ? '' : ' style="background-color: #eee;"') .'>
                          <td>' . $item->id . '</td>
                          <td><a href="' . $item->url . '" target="_blank">' . $item->url . '</a></td>
                          <td class="center"><a href="#" onClick="deleteRow(event, \'' . $item->id . '\')"><span class="fa fa-trash"></span></a></td>
                        </tr>
                      ');
                    }
                      
                    ?>
                    <tr>
                      <td>Create</td>
                      <td contenteditable id="url"></td>
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
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>
    <?php require_once(__DIR__ . "/Resources/Components/Scripts.php"); ?>
    <script>
      document.getElementById('url').addEventListener('paste', (event) => {
          event.preventDefault();
          const text = (event.originalEvent || event).clipboardData.getData('text/plain');
          document.execCommand('insertHTML', false, text);
      });

      function create(event) {
        event.preventDefault();
        const url = document.getElementById('url').innerText.trim();
        if (url) {
          postRequest('/Controllers/Admin/SSL.php', 'create', { url });
        }
      }

      function deleteRow(event, id) {
        event.preventDefault();
        postRequest('/Controllers/Admin/SSL.php', 'delete', { id });
      }

      function downloadTable() {
        postRequest('/Controllers/Admin/SSL.php', 'download');
      }
    </script>
</body>
</html>