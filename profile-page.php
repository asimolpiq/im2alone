<?php require('includes/db_connect.php');
require('includes/class.upload.php');
require_once('includes/csrf.php');
ob_start();
session_start();
if (!isset($_SESSION["im2alone_user"])) {
  header("Location:index.php");
  exit();
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
  <?php require('includes/librarys_app.php'); ?>

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
          if ($_FILES && isset($_POST['csrf_token']) && csrfTokenValid($_POST['csrf_token'])) {
            $image = $_FILES['profile_pic'];
            if ($image['name'] != "") {
              //real image check, class.upload alone lets svg/ico style image/* mimes through raw
              $img_info = @getimagesize($image['tmp_name']);
              $safe_types = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP, IMAGETYPE_BMP);
              if (is_array($image['name']) || $img_info === false || !in_array($img_info[2], $safe_types, true) || $img_info[0] * $img_info[1] > 25000000) {
                echo "<div class='alert alert-danger' role='alert'> Only jpg, png, gif or webp please. </div>";
              }
              else {
              $foo = new Upload($image);
              if ($foo->uploaded) {
                $foo->allowed = array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png', 'image/gif', 'image/webp', 'image/x-webp', 'image/bmp', 'image/x-ms-bmp');
                $foo->file_max_size = 5 * 1024 * 1024;
                $foo->image_convert = 'jpg'; //re-encode everything, kills hidden payloads
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
                    $_SESSION["im2alone_user"]['pp'] = $pp_save_path; //navbar/sidebar hemen yeni fotoyu gorsun
                    $im2alone_user['pp'] = $pp_save_path;
                    echo "<div class='alert alert-success' role='alert'> Profile Picture Upload Successful</div>";
                    header("Refresh:2; url=profile-page.php");
                    $foo->clean();
                    //delete the old picture only after the new one is really saved
                    if ($pp_path != $pp_save_path && strpos($pp_path, "dist/profile_pictures/") === 0) {
                      @unlink($pp_path);
                    }
                  } else {
                    echo "<div class='alert alert-danger' role='alert'>Profile Picture Can't Complete</div>";
                  }
                } else {
                  echo "<div class='alert alert-danger' role='alert'>Profile Picture Can't Complete</div>";
                }
              }
              }
            } else {
              echo "<div class='alert alert-danger' role='alert'> Select a profile pic dude. </div>";
            }
          }
        } else {
          $pp_path = $pp_row['pp'];
          if ($_FILES && isset($_POST['csrf_token']) && csrfTokenValid($_POST['csrf_token'])) {
            $image = $_FILES['profile_pic'];
            if ($image['name'] != "") {
              //real image check, class.upload alone lets svg/ico style image/* mimes through raw
              $img_info = @getimagesize($image['tmp_name']);
              $safe_types = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP, IMAGETYPE_BMP);
              if (is_array($image['name']) || $img_info === false || !in_array($img_info[2], $safe_types, true) || $img_info[0] * $img_info[1] > 25000000) {
                echo "<div class='alert alert-danger' role='alert'> Only jpg, png, gif or webp please. </div>";
              }
              else {
              $foo = new Upload($image);
              if ($foo->uploaded) {
                $foo->allowed = array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png', 'image/gif', 'image/webp', 'image/x-webp', 'image/bmp', 'image/x-ms-bmp');
                $foo->file_max_size = 5 * 1024 * 1024;
                $foo->image_convert = 'jpg'; //re-encode everything, kills hidden payloads
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
                    $_SESSION["im2alone_user"]['pp'] = $pp_save_path; //navbar/sidebar hemen yeni fotoyu gorsun
                    $im2alone_user['pp'] = $pp_save_path;
                    echo "<div class='alert alert-success' role='alert'> Profile Picture Upload Successful</div>";
                    header("Refresh:2; url=profile-page.php");
                    $foo->clean();
                    //delete the old picture only after the new one is really saved
                    if ($pp_path != $pp_save_path && strpos($pp_path, "dist/profile_pictures/") === 0) {
                      @unlink($pp_path);
                    }
                  } else {
                    echo "<div class='alert alert-danger' role='alert'>Profile Picture Can't Complete</div>";
                  }
                } else {
                  echo "<div class='alert alert-danger' role='alert'>Profile Picture Can't Complete</div>";
                }
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
              <p class="text-center"><?= htmlspecialchars($im2alone_user['bio'], ENT_QUOTES) ?></p>
              <p class="text-center"><button type="button" class="btn btn-primary btn-rounded btn-sm" data-toggle="modal" data-target="#avatarModal">Change Profile Picture</button></p>
            </div>
          </div>

          <!-- avatar degistirme pop-up'i -->
          <div class="modal fade" id="avatarModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Change Profile Picture</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form method="POST" enctype="multipart/form-data" class="avatar-upload">
                  <div class="modal-body">
                    <div class="avatar-preview">
                      <img id="avatar-preview-img" src="<?= $pp_path ?>" alt="Avatar preview">
                      <label for="avatar-input" class="avatar-overlay"><i class="fa fa-camera"></i></label>
                    </div>
                    <input type="file" name="profile_pic" id="avatar-input" accept=".png, .jpg, .jpeg, .webp" hidden>
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                    <p class="avatar-hint" id="avatar-hint">Click the photo to choose a new one</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-rounded btn-sm" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-rounded btn-sm" id="avatar-upload-btn" disabled>Upload</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <script>
          //secilen fotografi hemen yuvarlak onizlemede goster, upload'i aktive et
          document.getElementById('avatar-input').addEventListener('change', function () {
            var file = this.files[0];
            if (!file) { return; }
            var reader = new FileReader();
            reader.onload = function (e) {
              document.getElementById('avatar-preview-img').src = e.target.result;
            };
            reader.readAsDataURL(file);
            document.getElementById('avatar-hint').textContent = file.name;
            document.getElementById('avatar-upload-btn').disabled = false;
          });
          </script>
        </div>
      <?php
      $errors = "";
      if (isset($_SESSION['delete_error'])) { //delete_account.php'den donen hata
        $errors = "<div class='alert alert-danger text-center' role='alert'> " . $_SESSION['delete_error'] . " </div>";
        unset($_SESSION['delete_error']);
      }
      if (isset($_POST['update_profile'])) {
        if (!isset($_POST['csrf_token']) || !csrfTokenValid($_POST['csrf_token'])) {
          $errors = "<div class='alert alert-danger text-center' role='alert'> Session expired, please refresh the page and try again. </div>";
        } else {
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

        $my_id = (int) $im2alone_user['id'];

        //bos birakilan her alan oldugu gibi kalir (eskiden elseif zinciriydi,
        //sadece ilk bos alan korunuyordu, digerleri bos kaydediliyordu).
        //unique kontrollerinden ONCE calismali ki bos deger yanlis alarm vermesin
        if ($username == "") {
          $username = $im2alone_user['username'];
        }
        if ($realname == "") {
          $realname = $im2alone_user['realname'];
        }
        if ($email == "") {
          $email = $im2alone_user['email'];
        }
        if ($gender === "" || $gender === null) {
          $gender = $im2alone_user['gender'];
        }
        if ($bio == "") {
          $bio = $im2alone_user['bio'];
        }
        if ($_POST["birthday"] == "") {
          $birthday = $im2alone_user['birthday'];
        }

        $username_stmt = $conn->prepare("SELECT username FROM users WHERE username=? AND id<>?");
        $username_stmt->bind_param("si", $username, $my_id);
        $username_stmt->execute();
        $result = $username_stmt->get_result();
        $count = $result->num_rows;
        $username_stmt->close();
        if ($count != 0) {
          $error = true;
          $errors = "<div class='alert alert-danger' role='alert'> Username allready used:( </div>";
        }
        $email_stmt = $conn->prepare("SELECT email FROM users WHERE email=?");
        $email_stmt->bind_param("s", $email);
        $email_stmt->execute();
        $result = $email_stmt->get_result();
        $count = $result->num_rows;
        $email_stmt->close();
        if ($count != 0 && $email!=$im2alone_user['email']) {
          $error = true;
          $errors = "<div class='alert alert-danger' role='alert'> Email allready used dude :( </div>";
        }

        //sifre bos birakildiysa degistirilmez
        $password_changing = !($recive_password == "" && $confirm_pass == "");
        if (!$password_changing) {
          $password = $im2alone_user['password'];
        }

        if (strlen($username) < 6) {
          $errors = "<div class='alert alert-danger' role='alert'> username too short </div>";
        } elseif (strlen($username) > 30) {
          $errors = "<div class='alert alert-danger' role='alert'> username too long </div>";
        } elseif ($password_changing && strlen($recive_password) < 6) {
          $errors = "<div class='alert alert-danger' role='alert'> password too short </div>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $errors = "<div class='alert alert-danger' role='alert'> Invalid email format</div>";
        } elseif ($password_changing && $recive_password != $confirm_pass) {
          $errors = "<div class='alert alert-danger text-center' role='alert'> Passwords do not match dude :( </div>";
        } elseif (!preg_match('/^[0-9A-Za-z_]+$/', $username) || !preg_match('/^[\p{L}0-9_ ]+$/u', $realname)) {
          $errors = "<div class='alert alert-danger text-center' role='alert'> Username or Realname must contain alphabets and space dude :( </div>";
        } elseif (!$error) {
          $profile_update = $conn->prepare("UPDATE users SET username=?, realname=?, password=?, email=?, gender=?,birthday=?, bio=? WHERE id=?");
          $gender_int = (int) $gender;
          $profile_update->bind_param("ssssissi", $username, $realname, $password, $email, $gender_int, $birthday, $bio, $my_id);
          if ($profile_update->execute()) {
            //session'i da tazele ki sayfadaki degerler eski kalmasin
            $im2alone_user['username'] = $username;
            $im2alone_user['realname'] = $realname;
            $im2alone_user['password'] = $password;
            $im2alone_user['email'] = $email;
            $im2alone_user['gender'] = $gender_int;
            $im2alone_user['birthday'] = $birthday;
            $im2alone_user['bio'] = $bio;
            $_SESSION["im2alone_user"] = $im2alone_user;
            $errors = "<div class='alert alert-success text-center' role='alert'> Profile Update Succesful! </div>";
          } else {
            $errors = "<div class='alert alert-danger text-center' role='alert'> Profile Update Failed! </div>";
            header("Refresh:3; url=profile-page.php");
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
                        <input value="<?= htmlspecialchars($im2alone_user['username'], ENT_QUOTES) ?>" class="form-control form-control-line" type="text" name="username">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-12">Full Name</label>
                      <div class="col-md-12">
                        <input value="<?= htmlspecialchars($im2alone_user['realname'], ENT_QUOTES) ?>" class="form-control form-control-line" type="text" name="realname">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-12">Password</label>
                      <div class="col-md-12">
                        <input placeholder="Leave empty to keep your current password" class="form-control form-control-line" type="password" name="password">
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
                        <input value="<?= htmlspecialchars($im2alone_user['email'], ENT_QUOTES) ?>" class="form-control form-control-line" name="email" id="example-email" type="email">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-12">Gender</label>
                      <div class="col-md-12">
                        <select class="form-control" name="gender">
                          <option value="0" <?= $im2alone_user['gender'] == 0 ? 'selected' : '' ?>>Male</option>
                          <option value="1" <?= $im2alone_user['gender'] == 1 ? 'selected' : '' ?>>Female</option>
                          <option value="2" <?= $im2alone_user['gender'] == 2 ? 'selected' : '' ?>>Unisex</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-12">Birthday</label>
                      <div class="col-md-12">
                        <?php
                        //db'de gg/aa/yyyy tutuluyor, date input yyyy-aa-gg ister
                        $bday_ts = strtotime(str_replace('/', '-', $im2alone_user['birthday']));
                        $bday_value = $bday_ts ? date('Y-m-d', $bday_ts) : "";
                        ?>
                        <input class="form-control" id="date1" type="date" value="<?= $bday_value ?>" name="birthday">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-12">Bio</label>
                      <div class="col-md-12">
                        <textarea rows="5" class="form-control form-control-line" name="bio"><?= htmlspecialchars($im2alone_user['bio'], ENT_QUOTES) ?></textarea>
                      </div>
                    </div>
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
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

        <!-- hesap silme (danger zone) -->
        <div class="info-box danger-zone">
          <div class="card-body">
            <h5>Delete Account</h5>
            <p>Deleting your account will permanently remove your profile, all your diary posts, photos, friends, likes and messages. This can not be undone.</p>
            <button type="button" class="btn btn-danger btn-rounded" data-toggle="modal" data-target="#deleteAccountModal">Delete My Account</button>
          </div>
        </div>

        <div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Delete Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <form method="POST" action="delete_account.php">
                <div class="modal-body">
                  <p class="delete-warning">This will permanently delete your account and <strong>everything</strong> you shared. There is no way back.</p>
                  <label class="delete-label">Enter your password to confirm:</label>
                  <input type="password" class="form-control" name="password" required autocomplete="current-password">
                  <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-rounded btn-sm" data-dismiss="modal">Cancel</button>
                  <button type="submit" name="delete_account" class="btn btn-danger btn-rounded btn-sm">Delete Forever</button>
                </div>
              </form>
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