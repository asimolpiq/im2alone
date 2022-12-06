<?php
require('includes/db_connect.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require('PHPMailer/SMTP.php');
require('PHPMailer/Exception.php');
require('PHPMailer/PHPMailer.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Forgot Password</title>
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
        $username = trim($_POST['username']);
        $username = strip_tags($_POST['username']);
        $username = htmlspecialchars($_POST['username']);

        $email = trim($_POST['email']);
        $email = strip_tags($_POST['email']);
        $email = htmlspecialchars($_POST['email']);

        $user_exists = mysqli_query($conn,"SELECT * FROM users WHERE username='$username' AND email='$email'");
        $token_exists = mysqli_query($conn,"SELECT * FROM tokens WHERE username='$username'");
        if(mysqli_num_rows($user_exists)==0){
            echo "<div class='alert alert-danger' role='alert'> User not found! </div>";
        }
        elseif(mysqli_num_rows($token_exists)>0){
            echo "<div class='alert alert-danger' role='alert'> You allready have a recovery token. Please check your mail. </div>";
        }
        else{
            $token= bin2hex(random_bytes(16));
            $mail = new PHPMailer(true);
            try{
                $mail->SMTPDebug = 0; // hata ayiklama: 1 = hata ve mesaj, 2 = sadece mesaj
                $mail->isSMTP(); 
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'ssl'; // Güvenli baglanti icin ssl normal baglanti icin tls
                $mail->Host = "mail.im2alone.com"; // Mail sunucusuna ismi
                $mail->Port = 465; // Gucenli baglanti icin 465 Normal baglanti icin 587
                $mail->IsHTML(true);
                $mail->SetLanguage("en", "phpmailer/language");
                $mail->CharSet  ="utf-8";
                $mail->Username = "info@im2alone.com"; // Mail adresimizin kullanicı adi
                $mail->Password = "MAİL PASSWORD"; // Mail adresimizin sifresi
                $mail->SetFrom("info@im2alone.com", "Confirm System"); // Mail attigimizda gorulecek ismimiz
                $mail->AddAddress($email); // Maili gonderecegimiz kisi yani alici
                $mail->Subject = "Please confirm your account."; // Konu basligi
                $mail->Body = "Please click to link and confirm your account. <a href='https://im2alone.com/recovery.php?username=$username&token=$token'>Click Me!<a>"; // Mailin icerigi
                $mail->smtpConnect([
                'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
                ]
                ]);
                if(!$mail->Send()){
                    echo "Mailer Error: ".$mail->ErrorInfo;
                } else {
                mysqli_query($conn,"INSERT INTO tokens(username,token) VALUES ('$username','$token')");
                echo "<div class='alert alert-success' role='alert'> Recovery Mail Sended! </div>";
                header('Refresh:3; url=index.php');
                }
                
            }
            catch(Exception $e){
                print($e);
            }
        } 
    }   
      ?>
      <h3 class="login-box-msg text-black">Forgot Password</h3>
      <form action="" method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data">

        <div class="form-group has-feedback">
          <input type="text" class="form-control sty1" placeholder="Username" name="username" required>
        </div>
        <div class="form-group has-feedback">
          <input type="email" class="form-control sty1" placeholder="Email" name="email" required>
        </div>
          <!-- /.col -->
          <div class="col-xs-4 m-t-1">
            <button type="submit" name="forgot" class="btn btn-primary btn-block btn-flat">Send Recovery Mail</button>
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