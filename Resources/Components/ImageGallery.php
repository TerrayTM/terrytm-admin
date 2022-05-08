<?php

$optional_authentication = true;

require_once(__DIR__ . "/../../Partials/Authenticator.php");
require_once(__DIR__ . "/../../Partials/DatabaseConnector.php");

$images = null;
$group_id = -1;
$content_html = "";

if (isset($_GET['id'])) {
  $group = ImageGroup::where("link_id", $_GET['id'])->first();

  if ($group) {
    $images = $group->images;
    $group_id = $group->id;
  }
} else {
  $parts = explode("/", $_SERVER['REQUEST_URI']);

  if (count($parts) === 3 && $parts[1] === "image-group") { 
    $group = ImageGroup::where("link_id", $parts[2])->first();

    if ($group) {
      $images = $group->images;
      $group_id = $group->id;
    }
  }
}

if (!$images) {
  header("Location: https://terrytm.com");

  exit();
}

foreach ($images as $image) {
  $path = '/files/images/' . $group_id . '/' . $image->name;
  $data_path = $_SERVER['HTTP_HOST'] === "api.terrytm.com" ? "https://terrytm.com" . $path : $path;
  $content_html .= '<div class="no-outline"><div class="image" data-path="' . $data_path . '" style="background-image: url(https://terrytm.com' . $path . ');"></div></div>';
}

?>

<html>
  <head>
    <title>Terry&trade;</title>
    <link rel="shortcut icon" href="https://terrytm.com/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <style>
      .image {
        height: 100%;
        background-size: contain; 
        background-repeat: no-repeat; 
        background-position: center; 
      }

      .navigation .image {
        width: 64px;
        height: 64px;
      }

      .no-outline {
        outline: none;
      }

      .navigation-holder {
        width: 100%; 
        border-top: solid darkslategrey 1px;
        padding-top: 10px;
      }

      .inner-container {
        margin: auto;
        height: 64px;
        width: <?php echo(min(64 * $images->count(), 512) + 32); ?>px;
      }

      .main {
        width: 100%;
        height: 90%;
      }

      html,
      body {
        padding: 0;
        margin: 0;
        background-color: black;
        height: 100%;
      }
      
      .slick-list { 
        margin: auto; 
        padding: 0 !important; 
      }

      .control {
        position: absolute;
        height: 64px;
        width: 100%;
        z-index: 5;
        display: flex;
        align-items: center;
        padding: 0 26px;
        box-sizing: border-box;
      }

      .view {
        height: 100%; 
        display: flex; 
        flex-direction: column; 
        align-items: center;
      }

      .button {
        background-color: transparent;
        border: none;
        outline: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        padding: 8px;
        text-decoration: none;
      }

      .button:hover {
        background-color: #ffdddda6;
      }
    </style>
  </head>
  <body>
    <section class="control">
      <a class="button" onClick="downloadImage(event)" download id="download-link">Download</a>
    </section>
    <section class="view">
      <div class="main">
        <?php echo($content_html); ?>
      </div>
      <div class="navigation-holder">
        <div class="inner-container">
          <div class="navigation">
            <?php echo($content_html); ?>
          </div>
        </div>
      </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
      function downloadImage(event) {
        event.target.setAttribute('href', document.querySelector('.slick-current').children[0].getAttribute('data-path'));
      }

      $('.main').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        <?php echo($images->count() > 8 ? "asNavFor: '.navigation'," : ""); ?>
        infinite: true
      });

      $('.navigation').slick({
        slidesToShow: <?php echo(htmlspecialchars(min(8, $images->count()))); ?>,
        slidesToScroll: 1,
        asNavFor: '.main',
        dots: false,
        arrows: false,
        centerMode: true,
        focusOnSelect: true,
        infinite: true
      });
    </script>
  </body>
</html>
