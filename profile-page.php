<?php require('includes/db_connect.php');
require('includes/class.upload.php');
ob_start();
session_start();
if (!isset($_SESSION["im2alone_user"])) {
  header("Location:index.php");
} else {
  $im2alone_user = $_SESSION["im2alone_user"];
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Profile Page</title>
  <?php require('includes/librarys.php'); ?>

  <!-- DataTables -->
  <link rel="stylesheet" href="dist/plugins/datatables/css/dataTables.bootstrap.min.css">

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

<body class="skin-blue sidebar-mini">
  <div class="wrapper boxed-wrapper">
    <header class="main-header">
      <!-- Logo -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <?php
      require('includes/navbar.php');
      require('includes/left_menu.php');
      ?>
  </div>
  <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1 class="text-white">Profile Page</h1>
      <ol class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Pages</li>
        <li><i class="fa fa-angle-right"></i> Profile Page</li>
      </ol>
    </div>

    <!-- Main content -->
    <div class="content">
      <?php
      $username = $im2alone_user['username'];
      $get_pp = mysqli_query($conn, "SELECT pp FROM users WHERE username ='$username'"); //whats my friend username?
      while ($pp_row = mysqli_fetch_array($get_pp)) {
        if ($pp_row['pp'] == NULL) {
          $pp_path = "dist/img/img1.jpg";
          if ($_FILES) {
            $image = $_FILES['profile_pic'];
            if ($image['name'] != "") {
              $foo = new Upload($image);
              if ($foo->uploaded) {
                $foo->allowed = array('image/*');
                $foo->file_overwrite = true;
                $foo->file_new_name_body = "'pp'+$username";
                $foo->image_resize = true;
                $foo->image_ratio_crop = true;
                $foo->image_x = 600;
                $foo->image_y = 500;
                $foo->process('dist/profile_pictures/');
                if ($foo->processed) {
                  $pp_file_name = $foo->file_dst_name;
                  $pp_save_path = "dist/profile_pictures/" . "$pp_file_name";
                  $pp_save_query = "UPDATE users SET pp='$pp_save_path' WHERE username='$username'";
                  if ($conn->query($pp_save_query)) {
                    echo "<div class='alert alert-success' role='alert'> Profile Picture Upload Successful</div>";
                    header("Refresh:2; url=profile-page.php");
                    $foo->clean();
                  } else {
                    echo "<div class='alert alert-danger' role='alert'>Profile Picture Can't Complete</div>";
                  }
                } else {
                  echo "<div class='alert alert-danger' role='alert'>Profile Picture Can't Complete</div>";
                }
              }
            } else {
              echo "<div class='alert alert-danger' role='alert'> Select a profile pic dude. </div>";
            }
          }
        } else {
          $pp_path = $pp_row['pp'];
          if ($_FILES) {
            unlink($pp_path);
            $image = $_FILES['profile_pic'];
            if ($image['name'] != "") {
              $foo = new Upload($image);
              if ($foo->uploaded) {
                $foo->allowed = array('image/*');
                $foo->file_overwrite = true;
                $foo->file_new_name_body = "'pp'+$username";
                $foo->image_resize = true;
                $foo->image_ratio_crop = true;
                $foo->image_x = 600;
                $foo->image_y = 500;
                $foo->process('dist/profile_pictures/');
                if ($foo->processed) {
                  $pp_file_name = $foo->file_dst_name;
                  $pp_save_path = "dist/profile_pictures/" . "$pp_file_name";
                  $pp_save_query = "UPDATE users SET pp='$pp_save_path' WHERE username='$username'";
                  if ($conn->query($pp_save_query)) {
                    echo "<div class='alert alert-success' role='alert'> Profile Picture Upload Successful</div>";
                    header("Refresh:2; url=profile-page.php");
                    $foo->clean();
                  } else {
                    echo "<div class='alert alert-danger' role='alert'>Profile Picture Can't Complete</div>";
                  }
                } else {
                  echo "<div class='alert alert-danger' role='alert'>Profile Picture Can't Complete</div>";
                }
              }
            } else {
              echo "<div class='alert alert-danger' role='alert'> Select a profile pic dude. </div>";
            }
          }
        }
      }

      ?>
      <div class="row">
        <div class="col-lg-3">
          <div class=" m-b-3">
            <div class="box-profile text-white"> <img class="profile-user-img img-responsive img-circle m-b-2" src="<?= $pp_path ?>" alt="User profile picture">
              <h3 class="profile-username text-center"><?= $im2alone_user['username'] ?></h3>
              <p class="text-center">&copy; <?= $im2alone_user['realname'] ?></p>
              <p class="text-center"><?= $im2alone_user['bio'] ?></p>
            </div>
          </div>
          <div class="info-box">
            <form method="POST" enctype="multipart/form-data">
              <div class="box-body">
                <div class="embed-container maps">
                  <h5 class="text-center">Select Profile Pic</h5>
                  <input type="file" name="profile_pic" accept=".png, .jpg, .jpeg">
                  <button type="submit" class="btn btn-success btn-sm pull-right">Upload</button>
                </div>
            </form>
          </div>
          <!-- /.box-body -->
        </div>
      </div>
      <?php
      $errors = "";
      if (isset($_POST['update_profile'])) {
        $username = trim($_POST['username']);
        $username = strip_tags($_POST['username']);
        $username = htmlspecialchars($_POST['username']);

        $realname = trim($_POST['realname']);
        $realname = strip_tags($_POST['realname']);
        $realname = htmlspecialchars($_POST['realname']);

        $email = trim($_POST['email']);
        $email = strip_tags($_POST['email']);
        $email = htmlspecialchars($_POST['email']);

        $gender = $_POST['gender'];

        $birthday = date('d/m/Y', strtotime($_POST["birthday"]));

        $recive_password = trim($_POST['password']);
        $recive_password = strip_tags($_POST['password']);
        $recive_password = htmlspecialchars($_POST['password']);
        $password = md5($recive_password);
        $password = md5($password);

        $confirm_pass = trim($_POST['confirm_pass']);
        $confirm_pass = strip_tags($_POST['confirm_pass']);
        $confirm_pass = htmlspecialchars($_POST['confirm_pass']);

        $bio = trim($_POST['bio']);
        $error = false;

        $confirm_pass = $_POST['confirm_pass'];

        $query = "SELECT username FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $query);
        $count = mysqli_num_rows($result);
        if ($count != 0 && $username == $im2alone_user['username']) {
          $errors = "<div class='alert alert-danger' role='alert'> Username allready used:( </div>";
          header("Refresh:3; url=register.php");
        }
        $query = "SELECT email FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $query);
        $count = mysqli_num_rows($result);
        if ($count != 0 && $email!=$im2alone_user['email']) {
          $error = true;
          echo "<div class='alert alert-danger' role='alert'> Email allready used dude :( </div>";
          header("Refresh:3; url=register.php");
        }

        if ($username == "") {
          $username = $im2alone_user['username'];
        } elseif ($realname == "") {
          $realname = $im2alone_user['realname'];
        } elseif ($email == "") {
          $email = $im2alone_user['email'];
        } elseif ($gender == "") {
          $gender = $im2alone_user['gender'];
        } elseif ($bio == "emptys") {
          $bio = $im2alone_user['bio'];
        }

        if ($recive_password == "" && $confirm_pass == "") {
          $errors = "<div class='alert alert-warning text-center' role='alert'>It is mandatory to enter a password to make a transaction. </div>";
        } else {
          if (strlen($username) < 6) {
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
              $errors = "<div class='alert alert-danger text-center' role='alert'> Passwords do not match dude :( </div>";
            } else {
              if (!preg_match('^[0-9A-Za-z_]+$^', $username) && !preg_match('^[0-9A-Za-z_]+$^', $realname)) {
                $error = true;
                $errors = "<div class='alert alert-danger text-center' role='alert'> Username or Realname must contain alphabets and space dude :( </div>";
              } else {
                if (!$error) {
                  $profile_update = "UPDATE users SET username='$username', realname='$realname', password='$password', email='$email', gender='$gender',birthday='$birthday', bio='$bio' WHERE username='$username'";
                  if ($conn->query($profile_update)) {
                    $errors = "Profile Update Succesful!";
                    header("Refresh:3; url=profile-page.php");
                  } else {
                    $errors = "<div class='alert alert-danger text-center' role='alert'> Profile Update Failed! </div>";
                    header("Refresh:3; url=profile-page.php");
                  }
                }
              }
            }
          }
        }
      }






      ?>
      <div class="col-lg-9">
        <div class="info-box">
          <?= $errors ?>
          <div class="card tab-style1">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs profile-tab" role="tablist">
              <li class="nav-item"> <a class="nav-link passive" data-toggle="tab" href="#settings" role="tab">Profile Settings</a> </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
              <div class="tab-pane active" id="settings" role="tabpanel">
                <div class="card-body">
                  <form class="form-horizontal form-material" method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data">
                    <div class="form-group">
                      <label class="col-md-12">Username</label>
                      <div class="col-md-12">
                        <input placeholder="<?= $im2alone_user['username'] ?>" class="form-control form-control-line" type="text" name="username">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-12">Full Name</label>
                      <div class="col-md-12">
                        <input placeholder="<?= $im2alone_user['realname'] ?>" class="form-control form-control-line" type="text" name="realname">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-12">Password</label>
                      <div class="col-md-12">
                        <input class="form-control form-control-line" type="password" name="password">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-12">Confirm Password</label>
                      <div class="col-md-12">
                        <input class="form-control form-control-line" type="password" name="confirm_pass">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="example-email" class="col-md-12">Email</label>
                      <div class="col-md-12">
                        <input placeholder="<?= $im2alone_user['email'] ?>" class="form-control form-control-line" name="email" id="example-email" type="email">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-12">Gender</label>
                      <div class="col-md-12">
                        <select class="form-control" name="gender">
                          <option value="0">Male</option>
                          <option value="1">Female</option>
                          <option value="2">Unisex</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-12">Birthday</label>
                      <div class="col-md-12">
                        <input class="form-control" id="date1" type="date" placeholder="<?= $im2alone_user['birthday'] ?>" name="birthday" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-12">Bio</label>
                      <div class="col-md-12">
                        <textarea rows="5" value="emptys" class="form-control form-control-line" name="bio"></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-12">
                        <button class="btn btn-success" type="submit" name="update_profile">Update Profile</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Main row -->
  </div>
  <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">Version 1.2</div>
    Copyright © 2017 Yourdomian. All rights reserved.
  </footer>
  </div>
  <!-- ./wrapper -->

  <!-- jQuery 3 -->
  <script src="dist/js/jquery.min.js"></script>

  <!-- v4.0.0-alpha.6 -->
  <script src="dist/bootstrap/js/bootstrap.min.js"></script>

  <!-- template -->
  <script src="dist/js/niche.js"></script>

  <!-- jQuery UI 1.11.4 -->
  <script src="dist/plugins/jquery-ui/jquery-ui.min.js"></script>
</body>

</html>