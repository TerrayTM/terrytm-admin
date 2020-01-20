<?php

$login_error = isset($_GET['error']);
$optional_authentication = true;

require_once(__DIR__ . "/Partials/Authenticator.php");

if ($user_authenticated) {
  header("Location: /dashboard.php");

  exit();
}

require_once(__DIR__ . "/Resources/Components/Header.php");

?>

<body class="bg-gradient-primary">
  <div class="container">
    <div class="row justify-content-center">
    <div class="col-xl-10 col-lg-12 col-md-9">
      <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <div class="row">
        <div class="col-lg-6 d-none d-lg-block bg-login-image" style="background-image: url(/Resources/Images/Profile.png);"></div>
        <div class="col-lg-6">
          <div class="p-5">
          <div class="text-center">
            <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
          </div>
          <form class="user" action="/Controllers/Admin/Login.php" method="POST">
            <div class="form-group">
            <input type="text" class="form-control form-control-user" autocomplete placeholder="Username" name="username">
            </div>
            <div class="form-group">
            <input type="password" class="form-control form-control-user" autocomplete placeholder="Password" name="password">
            </div>
            <div class="form-group">
            <div class="custom-control custom-checkbox small">
              <input type="checkbox" class="custom-control-input" id="customCheck" name="remember">
              <label class="custom-control-label" for="customCheck">Remember Me</label>
            </div>
            </div>
            <?php echo($token_input); ?>
            <input type="submit" class="btn btn-primary btn-user btn-block" value="Login">
            <?php if ($login_error) echo('<p style="margin: 8px 0 0 0; text-align: center; color: red;">Incorrect username or password.</p>'); ?>
          </form>
          </div>
        </div>
        </div>
      </div>
      </div>
    </div>
    </div>
  </div>
  <?php require_once(__DIR__ . "/Resources/Components/Footer.php"); ?>
</body>
</html>