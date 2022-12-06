<?php require('includes/db_connect.php');
ob_start();
session_start();
if(!isset($_SESSION["im2alone_user"])){
  header("Location:index.php");
}
else {
  $im2alone_user = $_SESSION["im2alone_user"];
  $my_id = $im2alone_user['id'];
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Rooms</title>
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
    <!-- Content Header (Page header) -->
    
    
    <!-- Main content -->
    <div class="content">

    <div class="row">
    <div class="col-lg-3"></div>
      <div class="col-lg-6 m-b-3">
          <div> 
            <!-- Widget: user widget style 1 -->
            <div class="box box-widget widget-user-2"> 
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-yellow">
                <h3>Online Rooms</h3>
                <h5>Join a chat room :)</h5>
              </div>

              <ul class="products-list product-list-in-box">
                <?php
                $sonuc=mysqli_query($conn,"SHOW TABLES");
                                    
                while($satir=mysqli_fetch_array($sonuc))
                {
                    $table_name = $satir[0];
                if(strpos($table_name, "_room")){
                    $online_count_query = mysqli_query($conn,"SELECT COUNT(id) FROM chat_online WHERE room_name='$table_name'");
                    $online_count = mysqli_fetch_row($online_count_query);
                    $online_count = $online_count[0];
                    $room_new_name = str_replace("_room", "", $table_name);
                    echo "<li class='item'>
                    <div class='product-img'> <img src='dist/img/img1.gif' alt='Product Image'> </div>
                    <div class='product-info'> <a href='room.php?name=$table_name' class='product-title h4'>$room_new_name</a><span class='product-description'> Online Users : $online_count  </span> </div>
                  </li>";
                }
                } 
                ?>
                <!-- /.item -->
              </ul>
            </div>
            <!-- /.widget-user --> 
          </div>
        </div>
        <div class="col-lg-3"></div>
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