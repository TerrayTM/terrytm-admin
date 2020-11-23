<?php

$styles = '
  <style>
    #blogContainer {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr 1fr;
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
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Posts</h6>
            </div>
            <div class="card-body">
              <div id="blogContainer">
                <?php

                $posts = Blog::all();

                foreach ($posts as $post) {
                  echo('<a href="#" onClick="load(' . $post->id . ')">' . $post->name . '</a>');
                }

                ?>
              </div>
            </div>
          </div>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Editor</h6>
            </div>
            <div class="card-body">
              <div id="editorContainer" style="border: 20px solid #d5e6f3; border-radius: 10px; width: 850px; margin: auto; padding: 60px 0;"></div>
              <button onclick="save()">save</button>
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
    <?php require_once(__DIR__ . "/Resources/Components/Editor.php"); ?>
    <script>
      async function save() {
        try {
          const content = await editor.save();
          console.log(content);
        } catch (error) {
          console.log(error);
        }
      }

      function load(id) {
        asyncPostRequest('/Controllers/Admin/Blog.php', 'content', { id }).then((response) => {
          if (response.success && response.data.content) {
            editor.data = response.data.content;
          }
        });
      }
    </script>
</body>
</html>