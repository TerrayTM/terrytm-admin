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
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Posts</h6>
            </div>
            <div class="card-body">
              <div>
                <?php

                  $posts = Blog::all();

                  foreach ($posts as $post) {
                    echo('<div><a>' . $post->name . "</a></div>");
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
    </script>
</body>
</html>