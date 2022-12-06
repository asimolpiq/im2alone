<?php require('includes/db_connect.php');
ob_start();
session_start();
if(!isset($_SESSION["im2alone_user"])){
  header("Location:index.php");
}
else {
  $im2alone_user = $_SESSION["im2alone_user"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>My Diary</title>
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
        $id = $_GET['sil'];
        $sql = "DELETE FROM feeds WHERE id=$id";
        if ($conn->query($sql,) === TRUE) {
          
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
      $sonuc=mysqli_query($conn,"SELECT * FROM feeds WHERE user_id ='$user_id' ORDER BY id DESC");
      while($satir=mysqli_fetch_array($sonuc))
      {
        $date = $satir['date'];
    
        echo "<div class='info-box'>";
        echo "<div class='box box-widget'>";
        echo    "<div class='box-header with-border'>";
        echo      "<div class='user-block'> <img class='img-circle' src='dist/img/img1.jpg' alt='User Image'> <span class='username'><a href='#'>",$im2alone_user['username'],"</a></span> <span class='description'>",$date,"</span> </div>";
        echo    "</div>";
        echo    "<div class='box-body pad'><br>",$satir['content'];
        if($satir['link']!=""){
          $link = $satir['link'];
          $link = str_replace("track/","embed/track/",$link);
         
          echo "<iframe style='border-radius:12px' src='",
          $link,
          "?utm_source=generator' height='80' frameBorder='0' allowfullscreen='' allow='autoplay; clipboard-write; encrypted-media; fullscreen; '></iframe>"  ;
        }
        echo "<br><a href='?sil=" ,$satir['id'],"' class='btn btn-rounded btn-danger btn-outline btn-sm'>Delete</a></div>";
        echo    "</div>";
        echo    "</div>";
        
        
      }
    }
   ?>
                             
                      
         

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
