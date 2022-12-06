<?php 
require('includes/db_connect.php');
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
<html lang="tr">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Write Your Feelings</title>
<!-- Tell the browser to be responsive to screen width -->
<?php require('includes/librarys.php'); ?>
<script src="ckeditor/ckeditor.js"></script>

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
    <?php
      require('includes/navbar.php'); 
    require('includes/left_menu.php'); ?>
    <!-- /.sidebar --> 
  </aside>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    
    
    <!-- Main content -->
    <div class="content">
    <?php
    if (isset($_POST['feels_save']))
    {
      $content = $_POST['feels'];
      $link = trim($_POST['link']);
      $spoti = "spotify";
      $arama_sonucu=strstr($link,$spoti);
      $privacy = $_POST['privacy'];
      $user_id = $im2alone_user['id'];
      $date = date('l jS \of F Y h:i:s A');
      if($content==""){
        echo "<div class='alert alert-danger' role='alert'> Please fill in the text field. </div>";
      }
      elseif($arama_sonucu===FALSE){
        echo "<div class='alert alert-danger' role='alert'> Please paste a spotify link. </div>";
      }
      else{
        $feels_query = "INSERT INTO feeds (user_id,content,link,date,privacy) VALUES ('$user_id','$content','$link','$date','$privacy')";
        try{
          mysqli_query($conn,$feels_query);
          echo "<div class='alert alert-success' role='alert'> Save Successful! </div>";
          header("Refresh:3; url=my-diary.php");
        }
        catch(Exception $e){
          echo "<div class='alert alert-danger' role='alert'> Save Failed! Please review your text.</div>";  
        }
        
      }
      
     
  }
    ?>
    <div class="col-lg-12">
          <div class="card card-outline">
            <div class="card-header bg-light">
              <h5 class="text-white m-b-0">Write Your Feelings</h5>
            </div>
            <div class="card-body">
            <form class="uk-form-stacked uk-margin-medium-top" method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data">

            <textarea class="ckeditor"  name="feels" required></textarea>
              <br>
              <div class="form-group">
                <h5>Link:</h5>
                <input type="text" class="form-control" id="basicInput" name="link" placeholder="Leave a spotify link to remember these feelings dude.">
              </div>
              <div class="form-group">
              <h5>Privacy:</h5>
          <select class="custom-select form-control" id="location1" name="privacy" required>
            <option value="0">Only me</option>
            <option value="1">Only friends</option>
            <option value="2">Everyone</option>
          </select>
        </div>
              <button type="submit" name="feels_save" class="btn btn-success">save your thoughts</button>
            </form>
            </div>
          </div>
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

<script src="dist/plugins/popper/popper.min.js"></script> 
<script src="dist/bootstrap/js/bootstrap.beta.min.js"></script> 

<!-- template --> 
<script src="dist/js/niche.js"></script> 
</body>
</html>