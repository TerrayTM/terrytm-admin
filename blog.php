<?php

$styles = '
  <style>
    .post-container {
      border: 1px solid #d1d3e2;
      border-radius: .35rem;
    }

    .post-link {
      cursor: pointer;
      margin: 6px;
      font-size: 16px;
      box-shadow: none !important;
      border: none !important;
      background-color: cornflowerblue;
    }

    .post-link span {
      margin-left: 12px;
      pointer-events: none;
    }

    #editorContainer {
      padding: 0 12px; 
      border: solid 1px lightblue; 
      border-radius: 6px;
    }
  </style>
';

require_once(__DIR__ . "/Partials/Authenticator.php");
require_once(__DIR__ . "/Resources/Components/Header.php");
require_once(__DIR__ . "/Partials/DatabaseConnector.php");

$post = null;
$categories = ["Draft", "Travel", "Entertainment", "Miscellaneous"];

if (isset($_GET['edit'])) {
  $post = Blog::find($_GET['edit']);
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
            <h1 class="h3 mb-2 text-gray-800">Manage Blog</h1>
            <a href="#" onClick="downloadTable()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Download Table</a>
          </div>
          <div class="card shadow mb-4" style="display: <?php echo(isset($_GET['create']) || isset($_GET['edit']) ? "none" : "block"); ?>;">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Posts</h6>
            </div>
            <div class="card-body">
              <?php

              if (!isset($_GET['create']) && !isset($_GET['edit'])) {
                $posts = Blog::where("type", "!=", "Backup")->get();

                foreach ($categories as $category) {
                  echo('<div class="form-group"><label>' . $category . '</label>');
                  echo('<div class="form-group post-container">');
                  
                  foreach ($posts->where("type", $category)->sortByDesc("date") as $current) {
                    echo('<a href="/blog.php?edit='. $current->id . '" class="btn btn-info post-link">' . $current->name . '<span class="fa fa-star"></span></button>');
                  }

                  echo('<a href="/blog.php?create=true" class="btn btn-info post-link" style="text-decoration: none; background-color: green;">Create<span class="fa fa-plus-square"></span></a>');
                  echo('</div></div>');
                }
              }

              ?>
            </div>
          </div>
          <div class="card shadow mb-4" style="display: <?php echo(isset($_GET['create']) || isset($_GET['edit']) ? "block" : "none"); ?>;">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Editor</h6>
            </div>
            <div class="card-body">
              <form action="/Controllers/Admin/Blog.php" method="post" onsubmit="savePost(event)" id="postForm">
                <?php echo($token_input); ?>
                <input type="hidden" name="request" id="requestInput" value="<?php echo($post ? "edit" : "create"); ?>">
                <input type="hidden" name="id" value="<?php echo($post->id ?? ""); ?>">
                <input type="hidden" name="content" id="contentInput">
                <div class="form-group">
                  <label>Name</label>
                  <div class="input-group mb-3">
                    <input class="form-control" type="text" name="name" value="<?php echo($post->name ?? ""); ?>" required>
                    <div class="input-group-append">
                      <button class="btn btn-outline-secondary" onClick="viewBlog()" type="button" <?php echo($post ? '' : 'disabled'); ?>>View</button>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Type</label>
                  <select class="form-control" name="type">
                    <?php

                    foreach ($categories as $category) {
                      echo('<option' . ($post && $post->type === $category ? " selected " : "") . '>' . $category . '</option>');
                    }

                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Date</label>
                  <input class="form-control" type="date" name="date" value="<?php echo($post->date ?? ""); ?>">
                </div>
                <div class="form-group">
                  <label>Author</label>
                  <input class="form-control" type="text" name="author" value="<?php echo($post->author ?? ""); ?>">
                </div>
                <label>Editor <span style="color: blue; display: none" id="sizeWarning">(Image Size > 2MB)</span></label>
                <div class="form-group">
                  <div id="editorContainer"></div>
                </div>
                <div class="form-group" style="display: none;" id="errorLabel">
                  <div class="card bg-danger text-white shadow">
                    <div class="card-body" id="errorMessage"></div>
                  </div>
                </div>
                <button class="btn btn-success" type="submit">Save Post</button>
                <?php echo($post ? '<button class="btn btn-danger" onClick="deletePost(event)" id="deleteInput">Delete</button>' : ''); ?>
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
  <?php require_once(__DIR__ . "/Resources/Components/Editor.php"); ?>
  <script>
    let deleteCounter = 6;
    let deleteTimer = null;

    async function savePost(event) {
      const label = document.getElementById('errorLabel');
      label.style.display = "none";
      try {
        const content = await editor.save();
        document.getElementById('contentInput').value = JSON.stringify(content);
      } catch (error) {
        event.preventDefault();
        document.getElementById('errorMessage').innerText = error;
        label.style.display = "block";
      }
    }

    async function load(id) {
      await editor.isReady;
      asyncPostRequest('/Controllers/Admin/Blog.php', 'content', { id }).then((response) => {
        if (response.success && response.data.content) {
          editor.render(JSON.parse(response.data.content));
        }
      });
    }

    function deletePost(event) {
      event.preventDefault();
      if (deleteTimer) {
        clearTimeout(deleteTimer);
      }
      deleteCounter = Math.max(0, deleteCounter - 1);
      if (deleteCounter === 0) {
        document.getElementById('requestInput').value = 'delete';
        document.getElementById('deleteInput').disabled = true;
        document.getElementById('postForm').submit();
      } else {
        document.getElementById('deleteInput').innerText = `Delete (${deleteCounter})`;
        deleteTimer = setTimeout(() => {
          deleteCounter = 6;
          document.getElementById('deleteInput').innerText = 'Delete';
        }, 5000);
      }
    }

    function downloadTable() {
      postRequest('/Controllers/Admin/Blog.php', 'download');
    }

    function viewBlog() {
      window.open(<?php echo($post ? "'" . $post->url() . "'": "''") ?>, '_blank');
    }

    <?php
    
    if ($post) {
      echo('load(' . $post->id . ');');
    }

    ?>
  </script>
</body>
</html>
