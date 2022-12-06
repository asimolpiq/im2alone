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
<title>Social Setting</title>
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
      <h1 class="text-white text-center">Social Settings</h1>
    </div>
    
    <!-- Main content -->
    <div class="content">
    <div class="info-box">
    <div class="row">
        
        <!-- tablo başlangıç -->
       <div class="table-responsive col-lg-4">
            <h4 class="text-center">Friends</h4>
            <br>
            <table class="table">
              <thead class="bg-success">
                <tr>
                <th scope="col">#</th>
                <th scope="col">Username</th>
                <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                  <?php
                    $sonuc=mysqli_query($conn,"SELECT * FROM friends WHERE userid1='$my_id' OR userid2='$my_id'");
                    while($satir=mysqli_fetch_array($sonuc))
                    {   
                       $userid1 = $satir['userid1'];
                       $userid2 = $satir['userid2'];
                       if ($userid1 == $my_id) { //whic my friend id?
                        $friendid = $userid2;
                       } else {
                        $friendid = $userid1;
                       }

                       $get_username = mysqli_query($conn, "SELECT * FROM users WHERE id ='$friendid'"); //whats my friend username?
                       while ($satir1 = mysqli_fetch_array($get_username)) {
                        $pp = $satir1['pp'];
                        if ($satir1['pp'] == "") {
                          $pp = "dist/img/img5.jpg";
                        }
                       echo '<tr>';
                       echo '<td>',"<img class='img-circle img-responsive' src='$pp' width='50px' height='50px' alt='User Image' >",'</td>';
                       echo '<td>',"<a href='user_detail.php?username=",$satir1['username'],"'>",$satir1['username'],"</a>",'</td>';
                       echo "<td><a href='", "delete_friend.php?my_id=", $my_id, "&friend_id=", $friendid, "' class='btn btn-rounded btn-danger pull-right'>Remove Friend</a></td>";
                    }
                    }
                  ?>

              </tbody>
            </table>
            </div>
            <div class="table-responsive col-lg-4">
            <h4 class="text-center">Blocked Users</h4>
            <br>
            <table class="table">
              <thead class="bg-danger">
                <tr>
                <th scope="col">#</th>
                <th scope="col">Username</th>
                <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                  <?php
                    $sonuc2=mysqli_query($conn,"SELECT * FROM blockeduser WHERE userid1='$my_id'");
                    while($satir2=mysqli_fetch_array($sonuc2))
                    {   
                       $friendid = $satir2['userid2'];
                       $get_username = mysqli_query($conn, "SELECT * FROM users WHERE id ='$friendid'"); //whats my friend username?
                       while ($satir3 = mysqli_fetch_array($get_username)) {
                        $pp = $satir3['pp'];
                        if ($satir3['pp'] == "") {
                          $pp = "dist/img/img5.jpg";
                        }
                       echo '<tr>';
                       echo '<td>',"<img class='img-circle img-responsive' src='$pp' width='50px' height='50px' alt='User Image' >",'</td>';
                       echo '<td>',"<a href='user_detail.php?username=",$satir3['username'],"'>",$satir3['username'],"</a>",'</td>';
                       echo "<td><a href='", "delete_block.php?my_id=", $my_id, "&friend_id=", $friendid, "' class='btn btn-rounded btn-danger pull-right'>Remove Block</a></td>";
                    }
                    }
                  ?>

              </tbody>
            </table>
            </div>
            <div class="table-responsive col-lg-4">
            <h4 class="text-center">Awaiting Friend Request</h4>
            <br>
            <table class="table">
              <thead class="bg-warning">
                <tr>
                <th scope="col">#</th>
                <th scope="col">Username</th>
                <th scope="col"></th>
                <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                  <?php
                    $sonuc4=mysqli_query($conn,"SELECT * FROM friend_request WHERE receiver='$my_id'");
                    while($satir4=mysqli_fetch_array($sonuc4))
                    {   
                       $friendid = $satir4['sender'];
                       $get_username = mysqli_query($conn, "SELECT * FROM users WHERE id ='$friendid'"); //whats my friend username?
                       while ($satir5 = mysqli_fetch_array($get_username)) {
                        $pp = $satir5['pp'];
                        if ($satir5['pp'] == "") {
                          $pp = "dist/img/img5.jpg";
                        }
                       echo '<tr>';
                       echo '<td>',"<img class='img-circle img-responsive' src='$pp' width='50px' height='50px' alt='User Image' >",'</td>';
                       echo '<td>',"<a href='user_detail.php?username=",$satir5['username'],"'>",$satir5['username'],"</a>",'</td>';
                       echo "<td><a href='", "accept_friend.php?my_id=", $my_id, "&friend_id=", $friendid, "' class='btn btn-rounded btn-success btn-sm  pull-right'>Accept</a> </td>";
                       echo "<td><a href='", "refuse_friend.php?my_id=", $my_id, "&friend_id=", $friendid, "' class='btn btn-rounded btn-danger btn-sm pull-right'>Refuse</a></td>";
                    }
                    }
                  ?>

              </tbody>
            </table>
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
<script src="dist/bootstrap/js/bootstrap.min.js"></script> 

<!-- template --> 
<script src="dist/js/niche.js"></script>
</body>
</html>