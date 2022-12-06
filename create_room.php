<?php require('includes/db_connect.php');
ob_start();
session_start();
if(!isset($_SESSION["im2alone_user"])){
  
  header("Location:index.php");
}
else {
  $im2alone_user = $_SESSION["im2alone_user"];
  if($im2alone_user['permission']==0){
    header("Location:index.php");
  }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Room Create</title>
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
    <div class="content-header sty-one">
      <h1 class="text-white text-center">Room Create</h1>
      <ol class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Pages</li>
        <li><i class="fa fa-angle-right"></i> Blank Page</li>
      </ol>
    </div>
    
    <!-- Main content -->
    <div class="content">
    <?php
    if (isset($_POST['room_save']))
    {
      $room_name = $_POST['room_name'];
      $room_name = $room_name."_room";
      try{
        $room_create = mysqli_query($conn,"CREATE TABLE IF NOT EXISTS $room_name (id int NOT NULL AUTO_INCREMENT,username varchar(30) NOT NULL,message text NOT NULL,date varchar(70) NOT NULL,PRIMARY KEY(id))");
        if(isset($room_create)){
            echo "<div class='alert alert-success' role='alert'>Room Created. </div>";
            header("refresh:3; url=create_room.php");
        }
    }
      catch(Exception $e){
          echo "Error: ".$e;
      }
    }
    ?>
    <form class="uk-form-stacked uk-margin-medium-top" method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data">
      <div class="col-lg-4">
        <fieldset class="form-group">
          <label>Room Name:</label>
          <input class="form-control" id="basicInput" type="text" name="room_name" required>
        </fieldset>
      </div>
      <div class="col-lg-4">
        <fieldset class="form-group"></fieldset>
        <button type="submit" class="btn btn-success pull-right" name="room_save">Create</button>
      </fieldset>
      </div>

      </form>
      <br>
      <hr>
      <br>
        <!-- tablo başlangıç -->
          <?php
            if(isset($_GET['roomname'])){
              $roomname = $_GET['roomname'];
              $sql = "DROP TABLE $roomname";
              if ($conn->query($sql,) === TRUE) {
                echo "<div class='alert alert-success' role='alert'>Room Deleted. </div>";
                header("refresh:3; location:admin-create.php");
              } else {
                echo "Room delete failed: " . $conn->error;
              }
            }
          ?>
          <div class="table-responsive col-lg-12">
            <h4 class="text-center" >| Room List |</h4>
            <br>
            <table class="table">
              <thead>
                <tr>
                <th scope="col">Room Name</th>
                <th scope="col">Edit</th>
                </tr>
                </thead>
                <tbody>
                  <?php
                    $sonuc=mysqli_query($conn,"SHOW TABLES");
                    
                    while($satir=mysqli_fetch_array($sonuc))
                    {
                        $table_name = $satir[0];
                       if(strpos($table_name, "_room")){
                        $room_new_name = str_replace("_room", "", $table_name);
                        echo '<tr>';
                        echo '<td>',$room_new_name,'</td>';
                        echo "<td><a href='?roomname=" ,$table_name,"' class='btn btn-rounded btn-danger'>Delete</a></td>";
                       }
                       
                    } 

                  ?>

              </tbody>
            </table>
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