<?php
require('includes/db_connect.php');
require_once('includes/csrf.php');
require('includes/account_delete.php');
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Delete Your Account</title>
<?php require('includes/librarys.php'); ?>
</head>
<body class="hold-transition login-page sty1">
<div class="login-box sty1">
  <div class="login-box-body sty1">
  <div class="login-logo">
    <h1 class="text-black">I M 2 A L O N E</h1>
  </div>
    <h3 class="login-box-msg text-black">Delete Your Account</h3>

    <?php
    $deleted = false;
    if (isset($_POST['delete_account_public'])) {
      if (!isset($_POST['csrf_token']) || !csrfTokenValid($_POST['csrf_token'])) {
        echo "<div class='alert alert-danger' role='alert'> Session expired, please refresh the page and try again. </div>";
      }
      elseif (!isset($_POST['confirm_delete'])) {
        echo "<div class='alert alert-danger' role='alert'> Please confirm that you understand this can not be undone. </div>";
      }
      else {
        $username = trim($_POST['username']);
        $username = strip_tags($_POST['username']);
        $username = htmlspecialchars($_POST['username']);

        $recive_password = trim($_POST['password']);
        $password = md5(md5($recive_password));

        $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND password = ?");
        $check_stmt->bind_param("ss", $username, $password);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $user_row = $check_result->fetch_assoc();
        $check_stmt->close();

        if ($user_row) {
          if (deleteUserAccount($conn, (int) $user_row['id'])) {
            $deleted = true;
            session_unset();
            session_destroy();
            echo "<div class='alert alert-success' role='alert'> Your account and all your data (profile, diary posts, photos, friends, likes and messages) have been permanently deleted. Goodbye! </div>";
          } else {
            echo "<div class='alert alert-danger' role='alert'> Something went wrong, your account was not deleted. Please try again or contact <a href='mailto:info@im2alone.com'>info@im2alone.com</a>. </div>";
          }
        } else {
          echo "<div class='alert alert-danger' role='alert'> Username or password is wrong, account was not deleted. </div>";
        }
      }
    }
    if (!$deleted) {
    ?>
    <p class="text-black">Deleting your account will permanently remove your profile, all your diary posts, photos, friends, likes and chat messages. <strong>This can not be undone.</strong></p>
    <form action="" method="POST" accept-charset="UTF-8">
      <div class="form-group has-feedback">
        <input type="text" class="form-control sty1" placeholder="username" name="username" required>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control sty1" placeholder="password" name="password" required>
      </div>
      <div class="checkbox icheck">
        <label class="text-black">
          <input type="checkbox" value="1" name="confirm_delete" required>
          I understand my account and all my data will be permanently deleted.</label>
      </div>
      <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
      <button type="submit" name="delete_account_public" class="btn btn-danger btn-block btn-flat">Permanently Delete My Account</button>
    </form>
    <div class="m-t-2"><a href="index.php" class="text-center">Back to login</a> &middot; <a href="support.php">Support</a></div>
    <?php } else { ?>
    <div class="m-t-2"><a href="index.php" class="text-center">Back to login</a></div>
    <?php } ?>
  </div>
</div>

<!-- jQuery 3 -->
<script src="dist/js/jquery.min.js"></script>
<!-- v4.0.0-alpha.6 -->
<script src="dist/bootstrap/js/bootstrap.min.js"></script>
<!-- template -->
<script src="dist/js/niche.js"></script>
</body>
</html>
