<?php require('includes/db_connect.php');
ob_start();
session_start();
if(!isset($_SESSION["im2alone_user"])){
  header("Location:index.php");
}
else {
  $im2alone_user = $_SESSION["im2alone_user"];
  $post_id=$_GET['id'];
  $username=$_GET['username'];
  $get_post_query = mysqli_query($conn,"SELECT * FROM feeds WHERE id='$post_id'");
  $satir=mysqli_fetch_array($get_post_query);
  $content = $satir['content'];
  $link = $satir['link'];
  $date = $satir['date'];
  $privacy = $satir['privacy'];
  if($privacy==0){
      header("Location:index.php");
  }
  $title = substr($content,3,18);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?=$title?></title>
<!-- Sabit Kütüphaneleri çektiğimiz yer -->
<?php require('includes/librarys.php'); ?>
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
       echo $content;
       if ($link != "") {
          $link = str_replace("track/", "embed/track/", $link);

          echo "<p><br><iframe style='border-radius:12px' src='",
          $link,
          "?utm_source=generator' height='80' width='100%' frameBorder='0' allowfullscreen='' allow='autoplay; clipboard-write; encrypted-media; fullscreen; '></iframe></p>";
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
</body>
</html>