<?php
require('includes/db_connect.php');
require_once('includes/age_functions.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require('PHPMailer/SMTP.php');
require('PHPMailer/Exception.php');
require('PHPMailer/PHPMailer.php');
require('includes/mailer.php');
ob_start();
session_start();
if (isset($_SESSION["im2alone_user"])) {
  header("Location:dashboard.php");
  exit();
}
function GetIP()
{
  if (getenv("HTTP_CLIENT_IP")) {
    $ip = getenv("HTTP_CLIENT_IP");
  } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
    $ip = getenv("HTTP_X_FORWARDED_FOR");
    if (strstr($ip, ',')) {
      $tmp = explode(',', $ip);
      $ip = trim($tmp[0]);
    }
  } else {
    $ip = getenv("REMOTE_ADDR");
  }
  return $ip;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Register</title>
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
      if (isset($_POST['register'])) {
        $username = trim($_POST['username']);
        $username = strip_tags($_POST['username']);
        $username = htmlspecialchars($_POST['username']);

        $realname = trim($_POST['realname']);
        $realname = strip_tags($_POST['realname']);
        $realname = htmlspecialchars($_POST['realname']);

        $email = trim($_POST['email']);
        $email = strip_tags($_POST['email']);
        $email = htmlspecialchars($_POST['email']);

        $gender = intval($_POST['gender']);

        $recive_password = trim($_POST['password']);
        $recive_password = strip_tags($_POST['password']);
        $recive_password = htmlspecialchars($_POST['password']);
        $password = md5($recive_password);
        $password = md5($password);

        $confirm_pass = trim($_POST['confirm_pass']);
        $confirm_pass = strip_tags($_POST['confirm_pass']);
        $confirm_pass = htmlspecialchars($_POST['confirm_pass']);

        //strtotime bozuk tarihte false doner, date() onu 01/01/1970 yapiyordu; bos birakip asagida yakaliyoruz
        $bday_ts = isset($_POST["birthday"]) ? strtotime($_POST["birthday"]) : false;
        $birthday = $bday_ts ? normalizeBirthday(date('d/m/Y', $bday_ts)) : null;

        $error = false;

        if ($birthday === null) {
          echo "<div class='alert alert-danger' role='alert'> Invalid birthday </div>";
        } elseif (isUnderMinAge($birthday)) {
          //13 yas alti kayit olamaz (App Store yas siniflandirmasi)
          echo "<div class='alert alert-danger' role='alert'> You must be at least " . IM2ALONE_MIN_AGE . " years old to use im2alone </div>";
        } elseif (strlen($username) < 6) {
          echo "<div class='alert alert-danger' role='alert'> username too short </div>";
        } elseif (strlen($username) > 30) {
          echo "<div class='alert alert-danger' role='alert'> username too long </div>";
        } elseif (strlen($password) < 6) {
          echo "<div class='alert alert-danger' role='alert'> password too short </div>";
        } elseif (strlen($password) > 255) {
          echo "<div class='alert alert-danger' role='alert'> password too long </div>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          echo "<div class='alert alert-danger' role='alert'> Invalid email format</div>";
        } else {
          if ($recive_password != $confirm_pass) {
            $error = true;
            echo "<div class='alert alert-danger' role='alert'> Passwords do not match dude :( </div>";
            header("Refresh:3; url=register.php");
          } else {
            $query = "SELECT username FROM users WHERE username='$username'";
            $result = mysqli_query($conn, $query);
            $count = mysqli_num_rows($result);
            if ($count != 0) {
              $error = true;
              echo "<div class='alert alert-danger' role='alert'> Username allready used dude :( </div>";
              header("Refresh:3; url=register.php");
            } else {
              //eski kontrol && ile ikisini birden istiyordu ve pattern basa demirli degildi,
              //gecersiz username'ler kabul ediliyordu. || + anchored pattern sart.
              if (!preg_match('/^[0-9A-Za-z_]+$/', $username) || !preg_match('/^[\p{L}0-9_ ]+$/u', $realname)) {
                $error = true;
                echo "<div class='alert alert-danger' role='alert'> Username or Realname must contain alphabets and space dude :( </div>";
                header("Refresh:3; url=register.php");
              } else {
                $query = "SELECT email FROM users WHERE email='$email'";
                $result = mysqli_query($conn, $query);
                $count = mysqli_num_rows($result);
                if ($count != 0) {
                  $error = true;
                  echo "<div class='alert alert-danger' role='alert'> Email allready used dude :( </div>";
                  header("Refresh:3; url=register.php");
                } else {
                  if (!$error) {//genel kontrollerden geçtiyse kaydet
                    $user_save = "INSERT INTO users (username,realname,password,email,gender,birthday,bio,permission,status,eula_accepted_at)
                VALUES ('$username','$realname','$password','$email','$gender','$birthday','',0,0,NOW())";
                    if ($conn->query($user_save)) {
                            //login'deki UPDATE log calissin diye kullanicinin log satirini burada aciyoruz
                            $new_user_id = (int) $conn->insert_id;
                            $reg_ip = GetIP();
                            $reg_date = date("Y/m/d");
                            $log_stmt = $conn->prepare("INSERT INTO log (userid,date,ip) VALUES (?,?,?)");
                            $log_stmt->bind_param("iss", $new_user_id, $reg_date, $reg_ip);
                            $log_stmt->execute();
                            $log_stmt->close();
                            //confirm token + mail. mail patlarsa kayit bozulmaz,
                            //giristen sonra tekrar gonderme sansi verecegiz
                            $token = bin2hex(random_bytes(16));
                            $token_stmt = $conn->prepare("INSERT INTO tokens(username,token,type) VALUES (?,?,'confirm')");
                            $token_stmt->bind_param("ss", $username, $token);
                            $token_stmt->execute();
                            $token_stmt->close();
                            if (sendConfirmationMail($email, $username, $token)) {
                              echo "<div class='alert alert-success' role='alert'> User Create Succesful! Please check your email to confirm your account. </div>";
                            } else {
                              echo "<div class='alert alert-warning' role='alert'> User Create Succesful! We couldn't send the confirmation email right now — you can login and confirm it later. </div>";
                            }
                            header('Refresh:4; url=index.php');
                    } else {
                      echo "<div class='alert alert-danger' role='alert'> User Create Failed! </div>";
                      header("Refresh:3; url=register.php");
                    }
                  }
                }
              }
            }
          }
        }
      }


      ?>
      <h3 class="login-box-msg text-black">Register</h3>
      <form action="" method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data">

        <div class="form-group has-feedback">
          <input type="text" class="form-control sty1" placeholder="Username" name="username" required>
        </div>
        <div class="form-group has-feedback">
          <input type="text" class="form-control sty1" placeholder="Real Name" name="realname" required>
        </div>
        <div class="form-group has-feedback">
          <input type="email" class="form-control sty1" placeholder="Email" name="email" required>
        </div>
        <div class="form-group has-feedback">
          <h6 class="text-black">Gender</h6>
          <select class="form-control" name="gender" required>
            <option value="0">Male</option>
            <option value="1">Female</option>
            <option value="2">Unisex</option>
          </select>
        </div>
        <div class="form-group has-feedback">
          <input class="form-control" id="date1" type="date" placeholder="dd/mm/yyyy" name="birthday" required>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control sty1" placeholder="Password" name="password" required>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control sty1" placeholder="Confirm Password" name="confirm_pass" required>
        </div>
        <div>
          <div class="col-xs-8">
            <div class="checkbox icheck">
              <label>
                <input type="checkbox" value="1" name="terms" required>
                I agree to the <a href="terms.php" target="_blank">Terms of Service</a></label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-xs-4 m-t-1">
            <button type="submit" name="register" class="btn btn-primary btn-block btn-flat">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <div class="m-t-2">Already have an account? <a href="index.php" class="text-center">Login</a></div>
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