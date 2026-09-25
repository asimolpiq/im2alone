<?php require('includes/db_connect.php');
require('includes/html_sanitizer.php');
require('includes/csrf.php');
require('includes/feed_stats.php');
ob_start();
session_start();
if(!isset($_SESSION["im2alone_user"])){
  header("Location:index.php");
  exit();
}
else {
  $im2alone_user = $_SESSION["im2alone_user"];
  csrfToken();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>My Diary</title>
<?php require('includes/librarys_app.php'); ?>

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
              
  <?php
      $user_id = $im2alone_user['id'];
      $diaryisEmpty = "SELECT * FROM feeds WHERE user_id ='$user_id'";
      $result = mysqli_query($conn,$diaryisEmpty);
      $count = mysqli_num_rows($result);
      if(isset($_GET['sil'])){
        if(!isset($_GET['csrf']) || !csrfTokenValid($_GET['csrf'])){
          header("Location:my-diary.php");
          exit();
        }
        $id = (int) $_GET['sil'];
        $sil_stmt = $conn->prepare("DELETE FROM feeds WHERE id = ? AND user_id = ?");
        $sil_owner_id = (int) $im2alone_user['id'];
        $sil_stmt->bind_param("ii", $id, $sil_owner_id);
        if ($sil_stmt->execute() === TRUE) {
          $post_deleted = $conn->affected_rows > 0;
          $sil_stmt->close();
          if ($post_deleted) { //begeni ve goruntulenme kayitlari da gitsin
            mysqli_query($conn, "DELETE FROM feed_likes WHERE feed_id='$id'");
            mysqli_query($conn, "DELETE FROM feed_views WHERE feed_id='$id'");
          }
          header("refresh:3; location:admin-create.php");
        } else {
          echo "Admin can not deleted: " . $conn->error;
        }
      }
      if($count==0){
         echo "<div class='card'>
              <div class='card-body'>
                <h4 class='card-title'>You Have Not a Diary Post</h4>
                <p class='card-text'><strong>im2alone</strong> lets you keep your feelings in a diary.<br> All the posts you write will be stored according to the privacy you specify.</p>
                <a href='write-diary.php' class='btn btn-primary'>Let's write first diary post!</a>";
                echo    "</div>";
                echo    "</div>";
      }
      else{
      $date = null;
      //kendi avatarim (hardcoded placeholder yerine), taze DB'den al
      $my_pp = "dist/img/img1.jpg";
      $pp_q = mysqli_query($conn, "SELECT pp FROM users WHERE id='$user_id'");
      if ($pp_q && ($pp_row = mysqli_fetch_row($pp_q)) && $pp_row[0] != "") {
        $my_pp = $pp_row[0];
      }
      $sonuc=mysqli_query($conn,"SELECT * FROM feeds WHERE user_id ='$user_id' ORDER BY id DESC");
      while($satir=mysqli_fetch_array($sonuc))
      {
        $date = $satir['date'];
    
        echo "<div class='info-box'>";
        echo "<div class='box box-widget'>";
        echo    "<div class='box-header with-border'>";
        echo      "<div class='user-block'> <img class='img-circle' src='$my_pp' alt='User Image'> <span class='username'><a href='#'>",$im2alone_user['username'],"</a></span> <span class='description'>",$date,"</span> </div>";
        echo    "</div>";
        echo    "<div class='box-body pad'>",sanitizeDiaryHtml($satir['content']);
        if($satir['link']!=""){
          $link = $satir['link'];
          $link = str_replace("track/","embed/track/",$link);
          $link = htmlspecialchars($link, ENT_QUOTES);

          echo "<iframe style='border-radius:12px' src='",
          $link,
          "?utm_source=generator' height='80' frameBorder='0' allowfullscreen='' allow='autoplay; clipboard-write; encrypted-media; fullscreen; '></iframe>"  ;
        }
        echo "<br><a href='?sil=" ,$satir['id'],"&csrf=",htmlspecialchars(csrfToken(), ENT_QUOTES),"' class='btn btn-rounded btn-danger btn-outline btn-sm'>Delete</a></div>";
        $stats = getFeedStats($conn, $satir['id'], $user_id);
        echo feedActionsHtml($stats, $satir['id'], false); //kendi postun, sadece sayaclar
        echo    "</div>";
        echo    "</div>";
        
        
      }
    }
   ?>
                             
                      
         

    </div>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->
 <?php require('includes/birthday_confetti.php');
 require('includes/footer.php'); ?>
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
