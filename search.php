<?php require('includes/db_connect.php');
ob_start();
session_start();
if (!isset($_SESSION["im2alone_user"])) {
  header("Location:index.php");
} else {
  $im2alone_user = $_SESSION["im2alone_user"];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Search</title>
  <?php
  require("includes/librarys.php");
  ?>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

</head>

<body class="skin-blue sidebar-mini">
  <div class="wrapper boxed-wrapper">
    <header class="main-header">
      <?php
      require("includes/navbar.php");
      require("includes/left_menu.php");
      ?>
  </div>
  <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->


    <!-- Main content -->
    <div class="content">
      <?php
      $user_info;
      $arr = array();
      if (empty($_POST["search_text"])) {
        header("Location:index.php");
      } else {
        $search_text = $_POST["search_text"];
        $search_text = $conn->real_escape_string($search_text);
        $sorgu = "SELECT * FROM users WHERE username LIKE '%$search_text%'";
        $sonuc = mysqli_query($conn, $sorgu);
        $count = mysqli_num_rows($sonuc);
        if ($count != 0) {
          while ($satir = mysqli_fetch_array($sonuc)) {
            array_push($arr,$satir);
          }
        } else {
          echo "User not found!";
          header("Refresh:2; url=index.php");
        }
      }
     
      ?>
      <h3 class="text-center">Users</h3><hr>
      <div class="row">
        <?php
           foreach ($arr as $user_info) {
            $username = $user_info['username'];
            $realname= $user_info['realname'];
            $pp2 = $user_info['pp'];
            if ($user_info['pp'] == '') {
              $pp2 = 'dist/img/img5.jpg';
            }
            $bio = $user_info['bio'];
            $post_count;
            $follower_count;
            $following_count;
            $reciever_id=$user_info['id'];
            $posts_query = mysqli_query($conn,"SELECT COUNT(id) AS number FROM feeds WHERE user_id='$reciever_id'");
            while ($satir1 = mysqli_fetch_array($posts_query)) {
              $post_count= $satir1['number'];
            }
            $follower_query = mysqli_query($conn,"SELECT COUNT(id) AS number FROM friends WHERE userid2='$reciever_id'");
            while ($satir2 = mysqli_fetch_array($follower_query)) {
              $follower_count= $satir2['number'];
            }
            $following_query = mysqli_query($conn,"SELECT COUNT(id) AS number FROM friends WHERE userid1='$reciever_id'");
            while ($satir3 = mysqli_fetch_array($following_query)) {
              $following_count= $satir3['number'];
            }
           
            echo "
            <div class='col-lg-6'>
            <div class='info-box'>
            <!-- Widget: user widget style 1 -->
            <div class='box box-widget widget-user '>
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class='widget-user-header bg-primary'>
                <h3 class='widget-user-username text-center'><strong>$username</strong></h3>
                <h5 class='widget-user-desc text-center'><strong>$realname</strong></h5>
              </div>
              <div class='widget-user-image'> <img class='img-circle' src='$pp2' alt='User Avatar'> </div>
              <div class='box-footer'>
                <div class='text-center'>
                  <p> $bio</p>
                <a href='", "user_detail.php?username=", $username,"' class='btn btn-facebook btn-rounded margin-bottom' name='add_friend'>Look Profile</a>
                 
                </div>
                <div class='row'>
                  <div class='col-sm-4 border-right'>
                    <div class='description-block'>
                      <h5 class='description-header'>$post_count</h5>
                      <span class='description-text'>POST</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class='col-sm-4 border-right'>
                    <div class='description-block'>
                      <h5 class='description-header'>$follower_count</h5>
                      <span class='description-text'>FOLLOWERS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class='col-sm-4'>
                    <div class='description-block'>
                      <h5 class='description-header'>$following_count</h5>
                      <span class='description-text'>FOLLOWİNG</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
            </div>
            <!-- /.widget-user -->
          </div></div>";
          }
        ?>
     
      </div>

    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php require("includes/footer.php"); ?>
  </div>
  <!-- ./wrapper -->

  <!-- jQuery 3 -->
  <script src="dist/js/jquery.min.js"></script>

  <!-- v4.0.0-alpha.6 -->
  <script src="dist/bootstrap/js/bootstrap.min.js"></script>

  <!-- template -->
  <script src="dist/js/niche.js"></script>
</body>

</html>