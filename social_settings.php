<?php require('includes/db_connect.php');
require('includes/csrf.php');
ob_start();
session_start();
if(!isset($_SESSION["im2alone_user"])){
  header("Location:index.php");
  exit();
}
else {
  $im2alone_user = $_SESSION["im2alone_user"];
  $my_id = $im2alone_user['id'];
  $csrf = htmlspecialchars(csrfToken(), ENT_QUOTES);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Social Setting</title>
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
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1 class="text-white text-center">Social Settings</h1>
    </div>
    
    <!-- Main content -->
    <div class="content">
    <div class="info-box">
      <?php
        // sekme sayaçları için sorgular en üstte çalışıyor, sonuçlar aşağıda geziliyor
        $sonuc=mysqli_query($conn,"SELECT * FROM friends WHERE userid1='$my_id' OR userid2='$my_id'");
        $friends_count = mysqli_num_rows($sonuc);
        $sonuc2=mysqli_query($conn,"SELECT * FROM blockeduser WHERE userid1='$my_id'");
        $blocked_count = mysqli_num_rows($sonuc2);
        $sonuc4=mysqli_query($conn,"SELECT * FROM friend_request WHERE receiver='$my_id'");
        $requests_count = mysqli_num_rows($sonuc4);
      ?>
      <!-- Tab bar: Friends / Blocked Users / Awaiting Friend Request -->
      <ul class="nav nav-tabs social-tabs" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab-friends" role="tab"><i class="ti ti-user"></i> Friends <span class="tab-count tab-count-success"><?=$friends_count?></span></a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-blocked" role="tab"><i class="ti ti-lock"></i> Blocked <span class="tab-count tab-count-danger"><?=$blocked_count?></span></a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab-requests" role="tab"><i class="ti ti-time"></i> Requests <span class="tab-count tab-count-warning"><?=$requests_count?></span></a></li>
      </ul>
      <div class="tab-content">
        <!-- arkadaş listesi -->
        <div class="tab-pane active" id="tab-friends" role="tabpanel">
          <div class="user-list">
                  <?php
                    if($friends_count==0){
                      echo "<div class='user-list-empty'><i class='ti ti-face-smile'></i><p>You have no friends yet.</p><span>Search for a username and send a friend request!</span></div>";
                    }
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
                       echo "<div class='user-row'>";
                       echo   "<img class='img-circle' src='$pp' alt='User Image'>";
                       echo   "<div class='user-row-info'>";
                       echo     "<a class='user-row-name' href='user_detail.php?username=",$satir1['username'],"'>",$satir1['username'],"</a>";
                       echo     "<span class='user-row-sub'>",$satir1['realname'],"</span>";
                       echo   "</div>";
                       echo   "<div class='user-row-actions'>";
                       echo     "<a href='", "delete_friend.php?my_id=", $my_id, "&friend_id=", $friendid, "&csrf=", $csrf, "' class='btn btn-rounded btn-outline btn-sm'>Remove Friend</a>";
                       echo   "</div>";
                       echo "</div>";
                    }
                    }
                  ?>
          </div>
        </div>
        <!-- engellenen kullanıcılar -->
        <div class="tab-pane" id="tab-blocked" role="tabpanel">
          <div class="user-list">
                  <?php
                    if($blocked_count==0){
                      echo "<div class='user-list-empty'><i class='ti ti-lock'></i><p>You haven't blocked anyone.</p><span>Blocked users can't see your diaries or find you in search.</span></div>";
                    }
                    while($satir2=mysqli_fetch_array($sonuc2))
                    {
                       $friendid = $satir2['userid2'];
                       $get_username = mysqli_query($conn, "SELECT * FROM users WHERE id ='$friendid'"); //whats my friend username?
                       while ($satir3 = mysqli_fetch_array($get_username)) {
                        $pp = $satir3['pp'];
                        if ($satir3['pp'] == "") {
                          $pp = "dist/img/img5.jpg";
                        }
                       echo "<div class='user-row'>";
                       echo   "<img class='img-circle' src='$pp' alt='User Image'>";
                       echo   "<div class='user-row-info'>";
                       echo     "<span class='user-row-name'>",$satir3['username'],"</span>";
                       echo     "<span class='user-row-sub'>",$satir3['realname'],"</span>";
                       echo   "</div>";
                       echo   "<div class='user-row-actions'>";
                       echo     "<a href='", "delete_block.php?my_id=", $my_id, "&friend_id=", $friendid, "&csrf=", $csrf, "' class='btn btn-rounded btn-outline btn-sm'>Remove Block</a>";
                       echo   "</div>";
                       echo "</div>";
                    }
                    }
                  ?>
          </div>
        </div>
        <!-- bekleyen arkadaşlık istekleri -->
        <div class="tab-pane" id="tab-requests" role="tabpanel">
          <div class="user-list">
                  <?php
                    if($requests_count==0){
                      echo "<div class='user-list-empty'><i class='ti ti-time'></i><p>No pending friend requests.</p><span>When someone sends you a request, it will show up here.</span></div>";
                    }
                    while($satir4=mysqli_fetch_array($sonuc4))
                    {
                       $friendid = $satir4['sender'];
                       $get_username = mysqli_query($conn, "SELECT * FROM users WHERE id ='$friendid'"); //whats my friend username?
                       while ($satir5 = mysqli_fetch_array($get_username)) {
                        $pp = $satir5['pp'];
                        if ($satir5['pp'] == "") {
                          $pp = "dist/img/img5.jpg";
                        }
                       echo "<div class='user-row'>";
                       echo   "<img class='img-circle' src='$pp' alt='User Image'>";
                       echo   "<div class='user-row-info'>";
                       echo     "<a class='user-row-name' href='user_detail.php?username=",$satir5['username'],"'>",$satir5['username'],"</a>";
                       echo     "<span class='user-row-sub'>",$satir5['realname'],"</span>";
                       echo   "</div>";
                       echo   "<div class='user-row-actions'>";
                       echo     "<a href='", "accept_friend.php?my_id=", $my_id, "&friend_id=", $friendid, "&csrf=", $csrf, "' class='btn btn-rounded btn-success btn-sm'>Accept</a>";
                       echo     "<a href='", "refuse_friend.php?my_id=", $my_id, "&friend_id=", $friendid, "&csrf=", $csrf, "' class='btn btn-rounded btn-outline btn-sm'>Refuse</a>";
                       echo   "</div>";
                       echo "</div>";
                    }
                    }
                  ?>
          </div>
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