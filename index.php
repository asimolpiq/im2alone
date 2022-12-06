<?php require('includes/db_connect.php'); 
ob_start();
session_start();
if(isset($_SESSION["im2alone_user"])){
  header("Location:dashboard.php");
}
function GetIP(){
  if(getenv("HTTP_CLIENT_IP")) {
    $ip = getenv("HTTP_CLIENT_IP");
  } elseif(getenv("HTTP_X_FORWARDED_FOR")) {
    $ip = getenv("HTTP_X_FORWARDED_FOR");
    if (strstr($ip, ',')) {
      $tmp = explode (',', $ip);
      $ip = trim($tmp[0]);
    }
  } else {
    $ip = getenv("REMOTE_ADDR");
  }
  return $ip;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Login</title>
<?php require('includes/librarys.php'); ?>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script>
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
</script>
</head>
<body class="hold-transition login-page sty1">  <!-- body-bg-1 adlı background -->
<div class="login-box sty1">
  <div class="login-box-body sty1">
  <div class="login-logo">
    <h1 class="text-black">I M 2 A L O N E</h1>
  </div>
    <p class="login-box-msg">Sign in to start your session</p>

    <?php
    if (isset($_POST['login']))
    {
      $username = trim($_POST['username']);
      $username = strip_tags($_POST['username']);
      $username = htmlspecialchars($_POST['username']);

      $recive_password = trim($_POST['password']);
      $recive_password = strip_tags($_POST['password']);
      $recive_password = htmlspecialchars($_POST['password']);
      $password = md5($recive_password);
      $password = md5($password);
     
      $sonuc=mysqli_query($conn,"select * from users WHERE username ='$username' and password='$password'");
        if($sonuc ->num_rows>0){
          while($satir = $sonuc -> fetch_assoc()){
            if($username==$satir['username'] && $password==$satir['password']){
              echo "<div class='alert alert-success' role='alert'> Login Success! </div>";
              $online_query = mysqli_query($conn,"UPDATE users SET online='1' WHERE username='$username'");
              $userid=$satir['id'];
              $ip_adresi = GetIP();
              $currentdate=  date("Y/m/d");
              $online_query = mysqli_query($conn,"UPDATE log SET date='$currentdate' , ip='$ip_adresi' WHERE userid='$userid'");
              $_SESSION["im2alone_user"] = $satir;
                header("Refresh: 2; url=dashboard.php");
            }
          }
        }
        else{ echo "<div class='alert alert-danger' role='alert'> User Not Fooking Found Dude! </div>";} 
      }
      ob_end_flush();
    ?>

    <form action="" method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data">
      <div class="form-group has-feedback">
        <input type="text" class="form-control sty1" placeholder="username" name="username">
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control sty1" placeholder="password" name="password">
      </div>
      <div>
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <a href="forgot.php" class="pull-right"><i class="fa fa-lock"></i> Forgot your password?</a> </div>
        </div>
        <br>
        <!-- /.col -->
        <div class="col-xs-4 m-t-1">
          <button type="submit" name="login" class="btn btn-primary btn-block btn-flat">Login</button><br>
          <a href="register.php" class="btn btn-primary btn-block btn-flat">Or Register?</a> 
        </div>
      
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