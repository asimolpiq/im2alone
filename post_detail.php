<?php require('includes/db_connect.php');
require('includes/html_sanitizer.php');
require('includes/feed_stats.php');
ob_start();
session_start();
if(!isset($_SESSION["im2alone_user"])){
  header("Location:index.php");
  exit();
}
else {
  $im2alone_user = $_SESSION["im2alone_user"];
  $my_id = (int) $im2alone_user['id'];
  $post_id=(int)$_GET['id'];
  $username=$_GET['username'];
  $get_post_stmt = $conn->prepare("SELECT * FROM feeds WHERE id=?");
  $get_post_stmt->bind_param("i", $post_id);
  $get_post_stmt->execute();
  $get_post_query = $get_post_stmt->get_result();
  $satir=mysqli_fetch_array($get_post_query);
  $get_post_stmt->close();
  if($satir==null){
      header("Location:index.php");
      exit();
  }
  $content = $satir['content'];
  $link = $satir['link'];
  $date = $satir['date'];
  $privacy = $satir['privacy'];
  $owner_id = (int) $satir['user_id'];

  $can_see = false;
  if($owner_id==$my_id){
      $can_see = true;
  }
  elseif($privacy==2){
      $can_see = true;
  }
  elseif($privacy==1){
      $friend_stmt = $conn->prepare("SELECT id FROM friends WHERE (userid1=? AND userid2=?) OR (userid1=? AND userid2=?)");
      $friend_stmt->bind_param("iiii", $my_id, $owner_id, $owner_id, $my_id);
      $friend_stmt->execute();
      $friend_result = $friend_stmt->get_result();
      if($friend_result->num_rows>0){
          $can_see = true;
      }
      $friend_stmt->close();
  }
  if(!$can_see){
      header("Location:index.php");
      exit();
  }
  recordFeedView($conn, $post_id, $my_id, $owner_id);
  $post_stats = getFeedStats($conn, $post_id, $my_id);
  $title = htmlspecialchars(substr($content,3,18), ENT_QUOTES);
  $username = htmlspecialchars($username, ENT_QUOTES);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?=$title?></title>
<!-- Sabit Kütüphaneleri çektiğimiz yer -->
<?php require('includes/librarys_app.php'); ?>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5 shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<!-- sayfada veri tekrarını önleyen kod-->
<script>
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
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
    require('includes/left_menu.php'); ?>
    </div>
    <!-- /.sidebar --> 
  </aside>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
  <div class="content-header sty-one  text-center">
      <h1 class="text-white">Publisher : <strong><?=$username?></strong></h1>
    Publish date : <strong><?=$date?></strong> 
    </div>
    <!-- Main content -->
    <div class="content">

    <div class="info-box">
       <?php
       echo sanitizeDiaryHtml($content);
       if ($link != "") {
          $link = str_replace("track/", "embed/track/", $link);
          $link = htmlspecialchars($link, ENT_QUOTES);

          echo "<p><br><iframe style='border-radius:12px' src='",
          $link,
          "?utm_source=generator' height='80' width='100%' frameBorder='0' allowfullscreen='' allow='autoplay; clipboard-write; encrypted-media; fullscreen; '></iframe></p>";
        }

        echo feedActionsHtml($post_stats, $post_id, $owner_id != $my_id);
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
</body>
</html>