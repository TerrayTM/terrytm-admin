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
            <h1 class="h3 mb-2 text-gray-800">Manage Permissions</h1>
            <a href="#" onClick="downloadTable()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Download Table</a>
          </div>
          <?php

          if (isset($_GET['error'])) {
            echo('
              <div class="card bg-danger text-white shadow" style="margin-bottom: 24px;">
                <div class="card-body">Invalid model or rules.</div>
              </div>
            ');
          }

          ?>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Query Permissions</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Model</th>
                      <th>Rules</th>
                      <th>Link</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    $query_permissions = QueryPermission::all();

                    foreach ($query_permissions as $query_permission) {
                      echo('
                        <tr>
                          <td>' . $query_permission->model . '</td>
                          <td>' . $query_permission->rules . '</td>
                          <td><a href="' . $query_permission->generate_link() . '" target="_blank">' . $query_permission->generate_link() . '</a></td>
                          <td class="center"><a href="#" onClick="deleteRow(event, \'' . $query_permission->id . '\')"><span class="fa fa-trash"></span></a></td>
                        </tr>
                      ');
                    }
                      
                    ?>
                    <tr>
                      <td contenteditable id="model"></td>
                      <td contenteditable id="rules"></td>
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

    document.getElementById('model').addEventListener('paste', preventFormat);
    document.getElementById('rules').addEventListener('paste', preventFormat);

    function create(event) {
      event.preventDefault();
      document.getElementById('rules').style.backgroundColor = 'initial';
      const model = document.getElementById('model').innerText.trim();
      const rules = document.getElementById('rules').innerText.trim();
      if (!model || !rules) {
        return;
      }
      try {
        const argument = JSON.parse(rules);
        if (Object.keys(argument).some((key) => typeof argument[key] === 'object' || typeof argument[key] === 'array')) {
          throw "Rules must be a flattened object.";
        }
        postRequest('/Controllers/Admin/Permissions.php', 'create', { model, rules });
      } catch (exception) {
        document.getElementById('rules').style.backgroundColor = '#eee';
      }
    }

    function deleteRow(event, id) {
      event.preventDefault();
      postRequest('/Controllers/Admin/Permissions.php', 'delete', { id });
    }

    function downloadTable() {
      postRequest('/Controllers/Admin/Permissions.php', 'download');
    }
  </script>
</body>
</html>