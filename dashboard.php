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
  <title>Dashboard</title>
  <?php require('includes/librarys.php'); ?>

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
      <!-- Logo -->


      <!-- sidebar menu: : style can be found in sidebar.less -->
      <?php
      require('includes/navbar.php');
      require('includes/left_menu.php'); ?>
  </div>
  <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <div class="content">
      <div class="row">
      <?php
      $user_id = $im2alone_user['id'];
      $date = null;
      $friendid = null;
      $friend_name = null;
      $arr = array();


      $query = "SELECT * FROM friends WHERE userid1='$user_id' OR userid2='$user_id' ORDER BY id DESC";
      $result = mysqli_query($conn, $query);
      $count = mysqli_num_rows($result);
      if ($count != 0) { // ı have a friend. 
        while ($friend_rows = mysqli_fetch_array($result)) {

          $userid1 = $friend_rows['userid1'];
          $userid2 = $friend_rows['userid2'];

          if ($userid1 == $user_id) { //whic my friend id?
            $friendid = $userid2;
          } else {
            $friendid = $userid1;
          }

          $friend_posts = mysqli_query($conn, "SELECT * FROM feeds WHERE (privacy='1' OR privacy='2') AND user_id ='$friendid' ORDER BY id DESC");
          $friendpostisempty = mysqli_num_rows($friend_posts);
        
            while ($post_rows = mysqli_fetch_array($friend_posts)) { //my friend have posts
              array_push($arr, $post_rows); //friend post push to array
            }
          
        }
      } else { //ı dont have a friend :(
        echo "<div class='card'>
              <div class='card-body'>
                <h4 class='card-title'>You Have Not a Friend</h4>
                <p class='card-text'><strong>im2alone</strong> lets you keep your feelings in a diary.<br> All the posts you write will be stored according to the privacy you specify.</p>
                <a href='write-diary.php' class='btn btn-primary'>Let's write first diary post!</a>";
        echo    "</div>";
        echo    "</div>";
      }

      function sort_date($key) //array date sort function
      {
        return function ($lt, $rt) use ($key) {
          if ($lt[$key] < $rt[$key]) {
            return 1;
          } elseif ($lt[$key] > $rt[$key]) {
            return -1;
          } else {
            return 0;
          }
        };
      }
      if ($arr == null) { //my friend post is empty?
        echo "<div class='card'>
            <div class='card-body'>
              <h4 class='card-title'>Your Friend Not Have a Post!</h4>
              <p class='card-text'><strong>im2alone</strong> lets you keep your feelings in a diary.<br> All the posts you write will be stored according to the privacy you specify.</p>";
        echo    "</div>";
        echo    "</div>";
      } 
      else {
      $column = sort_date('id');
      usort($arr, $column); //array sort callback

      foreach ($arr as $post_rows) { //for each the sort
        $date = $post_rows['date'];
        $friendid = $post_rows['user_id'];
        $get_username = mysqli_query($conn, "SELECT * FROM users WHERE id ='$friendid'"); //whats my friend username?
        while ($frnd_usrnm = mysqli_fetch_array($get_username)) {
          $friend_name = $frnd_usrnm['username']; //it's
          $pp = $frnd_usrnm['pp'];
          if ($frnd_usrnm['pp'] == "") {
            $pp = "dist/img/img5.jpg";
          }
        }
        $content =$post_rows['content'];
        $uzunluk = strlen($content);
        $limit =800;
        if($uzunluk>$limit){
          $content = substr($content,0,$limit);
        }
        echo  "<div class='col-lg-3'>";
        echo     "</div>";
        echo  "<div class='col-lg-6 m-b-3'> ";
        echo "<div class='info-box'>";
        echo "<div class='box box-widget '>";
        echo    "<div class='box-header with-border'>";
        echo      "<div class='user-block'> <img class='img-circle' src='$pp' alt='User Image'> <span class='username'><a href='user_detail.php?username=",$friend_name,"'>", $friend_name, "</a></span> <span class='description'>", $date, "</span> </div>";
        echo    "</div>";
        echo    "<div class='box-body pad'>";
        if($uzunluk>$limit){
          echo $content."...";
        }
        else{
          echo $content;
        }
        if ($post_rows['link'] != "") {
          $link = $post_rows['link'];
          $link = str_replace("track/", "embed/track/", $link);

          echo "<p><br><iframe style='border-radius:12px' src='",
          $link,
          "?utm_source=generator' height='80' width='100%' frameBorder='0' allowfullscreen='' allow='autoplay; clipboard-write; encrypted-media; fullscreen; '></iframe></p>";
        }
        if($uzunluk>$limit){
          $post_id = $post_rows['id'];
          echo  "<a href='post_detail.php?id=$post_id&username=$friend_name' class='btn btn-primary pull-right'>Show More</a>";
        }
        echo     "</div>";
        echo     "</div>";
        echo    "</div>";
        echo    "</div>";
        echo  "<div class='col-lg-3'>";
        echo     "</div>";
        
      }
    }
      ?>
    </div>
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php require('includes/footer.php'); ?>
  </div>
  <!-- ./wrapper -->

  <!-- jQuery 3 -->
  <script src="dist/js/jquery.min.js"></script>

  <!-- v4.0.0-alpha.6 -->
  <script src="dist/bootstrap/js/bootstrap.min.js"></script>

  <!-- template -->
  <script src="dist/js/niche.js"></script>

  <!-- Morris JavaScript -->
  <script src="dist/plugins/raphael/raphael-min.js"></script>
  <script src="dist/plugins/morris/morris.js"></script>
  <script src="dist/plugins/functions/dashboard1.js"></script>
</body>

</html>