<?php

$styles = '
  <style>
    .full-width {
      width: 100%;
    }

    .disabled a {
      cursor: not-allowed;
      color: lightgray !important;
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
            <h1 class="h3 mb-2 text-gray-800">Manage Images</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Download Table</a>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
              <h6 class="m-0 font-weight-bold text-primary">Image Groups</h6>
              <button class="form-control" style="width: 64px;" onClick="createGroup()">New</button>
            </div>
            <div class="card-body">
              <?php 
                
              if (isset($_GET['error'])) {
                echo('
                  <div class="card bg-danger text-white shadow" style="margin-bottom: 24px;">
                    <div class="card-body">Operation failed! Please try again later.</div>
                  </div>
                ');
              }
              
              ?>
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Created</th>
                      <th>Count</th>
                      <th>Upload</th>
                      <th>Manage</th>
                      <th>Rename</th>
                      <th>Refresh</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    $image_groups = ImageGroup::orderBy("id", "DESC")->get();

                    foreach ($image_groups as $image_group) {
                      $disabled = $image_group->is_deleted;

                      echo('
                        <tr' . ($disabled ? ' style="background-color: #eee;"' : '') . '>
                          <td><a href="' . $image_group->url() . '" target="_blank">' . $image_group->id . '</a></td>
                          <td contenteditable spellcheck="false" id="A' . $image_group->id . '">' . $image_group->name . '</td>
                          <td>' . $image_group->date . '</td>
                          <td>' . $image_group->images()->count() . '</td>
                          <td class="center' . ($disabled ? ' disabled' : '') . '"><a href="#" onClick="upload(event, \'' . ($disabled ? -1 : $image_group->id) . '\')"><span class="fa fa-upload"></span></a></td>
                          <td class="center"><a href="' . $image_group->admin_url() . '" target="_blank"><span class="fa fa-link"></span></a></td>
                          <td class="center' . ($disabled ? ' disabled' : '') . '"><a href="#" onClick="renameGroup(event, \'' . ($disabled ? -1 : $image_group->id) . '\')"><span class="fa fa-edit"></span></a></td>
                          <td class="center' . ($disabled ? ' disabled' : '') . '"><a href="#" onClick="refreshGroup(event, \'' . ($disabled ? -1 : $image_group->id) . '\')"><span class="fa fa-sync"></span></a></td>
                          <td class="center"><a href="#" onClick="' . ($disabled ? 'restoreGroup' : 'deleteGroup') . '(event, \'' . $image_group->id . '\')"><span class="fa fa-trash' . ($disabled ? '-restore' : '') . '"></span></a></td>
                        </tr>
                      ');
                    }

                    ?>
                  </tbody>
                </table>
              </div>
              <form action="/Controllers/Admin/Images.php" method="post" id="upload" style="display: none;" enctype="multipart/form-data">
                <?php echo($token_input); ?>
                <input type="hidden" name="request" value="upload">
                <input type="hidden" id="id" name="id" value="-1">
                <input type="file" id="images" name="images[]" onChange="postUpload()" multiple accept="image/*">
              </form>
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
    function postUpload() {
      document.getElementById('upload').submit();
    }

    function upload(event, id) {
      event.preventDefault();
      document.getElementById('id').value = id;
      id !== '-1' && document.getElementById('images').click();
    }

    function deleteGroup(event, id) {
      event.preventDefault();
      postRequest('/Controllers/Admin/Images.php', 'delete', { id });
    }

    function restoreGroup(event, id) {
      event.preventDefault();
      postRequest('/Controllers/Admin/Images.php', 'restore', { id });
    }

    function renameGroup(event, id) {
      event.preventDefault();
      const element = document.getElementById(`A${id}`);
      if (element && element.innerText.trim() && id !== '-1') {
        postRequest('/Controllers/Admin/Images.php', 'rename', { id, name: element.innerText.trim() });
      }
    }

    function refreshGroup(event, id) {
      event.preventDefault();
      id !== '-1' && postRequest('/Controllers/Admin/Images.php', 'refresh', { id });
    }

    function createGroup() {
      postRequest('/Controllers/Admin/Images.php', 'create');
    }
  </script>
</body>
</html>
