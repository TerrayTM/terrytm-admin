<?php

$styles = '
  <style>
    .center {
      text-align: center;
    }
  </style>
';

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
            <h1 class="h3 mb-2 text-gray-800">Manage Servers</h1>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Servers</h6>
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

                      $servers = Server::orderBy("id")->get();

                      foreach ($servers as $server) {
                        echo('
                          <tr>
                            <td>' . $server->id . '</td>
                            <td><a href="' . $server->url . '" target="_blank">' . $server->url . '</a></td>
                            <td class="center"><a href="#" onClick="deleteRow(event, \'' . $server->id . '\')"><span class="fa fa-trash"></span></a></td>
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
      document.getElementById('url').addEventListener('paste', (event) => {
          event.preventDefault();
          const text = (event.originalEvent || event).clipboardData.getData('text/plain');
          document.execCommand('insertHTML', false, text);
      });

      function create(event) {
        event.preventDefault();
        const url = document.getElementById('url').innerText;
        if (url) {
          postRequest('/Controllers/Admin/Servers.php', 'create', { url });
        }
      }

      function deleteRow(event, id) {
        event.preventDefault();
        postRequest('/Controllers/Admin/Servers.php', 'delete', { id });
      }
    </script>
</body>
</html>