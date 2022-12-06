<?php
require('includes/db_connect.php');
if(isset($_GET['username'])&& isset($_GET['token'])){
  $username = $_GET['username'];
  $token = $_GET['token'];
  $usr_query = mysqli_query($conn,"SELECT users.username,tokens.token FROM users,tokens WHERE users.username = tokens.username AND (users.username='$username' AND tokens.token = '$token')");
  if(mysqli_num_rows($usr_query)==0){
      header('Location:index.php');
  }
}
else{
  header('Location:index.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Password Reset</title>
  <?php require('includes/librarys.php'); ?>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
  <script>
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-box-body">

      <?php
      if (isset($_POST['forgot'])) {

        $recive_password = trim($_POST['password']);
        $recive_password = strip_tags($_POST['password']);
        $recive_password = htmlspecialchars($_POST['password']);
        $password = md5($recive_password);
        $password = md5($password);

        $confirm_pass = trim($_POST['confirm_pass']);
        $confirm_pass = strip_tags($_POST['confirm_pass']);
        $confirm_pass = htmlspecialchars($_POST['confirm_pass']);
        $error = false;
        if (strlen($password) < 6) {
          echo "<div class='alert alert-danger' role='alert'> password too short </div>";
        } elseif (strlen($password) > 255) {
          echo "<div class='alert alert-danger' role='alert'> password too long </div>";
        } else {
          if ($recive_password != $confirm_pass) {
            $error = true;
            echo "<div class='alert alert-danger' role='alert'> Passwords do not match </div>";
          } else {
            $query = "SELECT * FROM users WHERE username='$username'";
            $result = mysqli_query($conn, $query);
            while($satir = mysqli_fetch_array($result)){
              $usr_pass = $satir['password'];
              $usr_pass = md5($usr_pass);
              $usr_pass = md5($usr_pass);
              if ($password == $usr_pass) {
                $error = true;
                echo "<div class='alert alert-danger' role='alert'> Password allready your passwords :( </div>";
              } else {
               
                    if (!$error) {//genel kontrollerden geçtiyse kaydet
                      try{
                        mysqli_query($conn,"UPDATE users SET password='$password' WHERE username='$username'");
                        mysqli_query($conn,"DELETE FROM tokens WHERE token='$token'");
                        echo "<div class='alert alert-success' role='alert'> Password reset success! </div>";
                        header("Refresh:3;url=index.php");
                      }
                      catch(Exception $e){
                        echo $e;
                      }
                        } else {
                          echo "<div class='alert alert-danger' role='alert'> Password reset Failed! </div>";
                        }
                      }
            }
          
                  }
                }
              }
            


      ?>
      <h3 class="login-box-msg text-black">Reset Passwords</h3>
      <form action="" method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data">
        <div class="form-group has-feedback">
          <input type="password" class="form-control sty1" placeholder="Password" name="password" required>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control sty1" placeholder="Confirm Password" name="confirm_pass" required>
        </div>
        <div>
          <!-- /.col -->
          <div class="col-xs-4 m-t-1">
            <button type="submit" name="forgot" class="btn btn-primary btn-block btn-flat">Reset Passwords</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-box-body -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery 3 -->
  <script src="dist/js/jquery.min.js"></script>

  <!-- v4.0.0-alpha.6 -->
  <script src="dist/bootstrap/js/bootstrap.min.js"></script>

  <!-- template -->
  <script src="dist/js/niche.js"></script>
</body>

</html>