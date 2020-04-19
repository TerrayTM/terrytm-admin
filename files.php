<?php

$styles = '
  <style>
    .text {
      border: 1px solid #ccc;
      outline: none;
    }
  </style>
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
            <h1 class="h3 mb-2 text-gray-800">Manage Files</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Download Table</a>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Files</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>URL</th>
                        <th>File Size</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php

                        $path = __DIR__ . "/../files";
                        $files = @array_diff(@scandir($path), [".", "..", "images"]);

                        if ($files) {
                          foreach ($files as $file) {
                            echo('
                              <tr>
                                <td>' . $file . '</td>
                                <td><a href="https://terrytm.com/files/' . $file . '" target="_blank">https://terrytm.com/files/' . $file . '</a></td>
                                <td>' . filesize($path . "/" . $file) . '</td>
                                <td class="center"><a href="#" onClick="deleteFile(event, \'' . $file . '\')"><span class="fa fa-trash"></span></a></td>
                              </tr>
                            ');
                          }
                        }

                      ?>
                      <tr>
                        <td>Create</td>
                        <td></td>
                        <td></td>
                        <td class="center"><a href="#" onClick="create(event)"><span class="fa fa-save"></span></a></td>
                        <form id="uploadForm" action="/Controllers/Admin/Files.php" method="post" enctype="multipart/form-data">
                          <?php echo($token_input); ?>
                          <input type="hidden" name="request" value="create">
                          <input type="file" name="file" style="display: none;" id="uploadInput" onChange="onUpload()">
                        </form>
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
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>
    <?php require_once(__DIR__ . "/Resources/Components/Footer.php"); ?>
    <script>
      function onUpload() {
        if (document.getElementById('uploadInput').files.length > 0) {
          document.getElementById('uploadForm').submit();
        }
      }
                        
      function create(event) {
        event.preventDefault();
        document.getElementById('uploadInput').click();
      }

      function deleteFile(event, name) {
        event.preventDefault();
        postRequest('/Controllers/Admin/Files.php', 'delete', { name });
      }
    </script>
</body>
</html>