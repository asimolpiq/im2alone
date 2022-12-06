<?php require('includes/db_connect.php');
ob_start();
session_start();
if(!isset($_SESSION["im2alone_user"])){
  header("Location:index.php");
}
else {
  $im2alone_user = $_SESSION["im2alone_user"];
  $my_username = $im2alone_user['username'];
  $my_pp = $im2alone_user['pp'];
  if($my_pp==null){
    $my_pp = "dist/img/img2.jpg";
}
if (empty($_GET["name"])) {
    header("Location:index.php");
} else {
    $name = $_GET["name"];
    try{
      mysqli_query($conn,"INSERT INTO chat_online(room_name,username) VALUES ('$name','$my_username')");
    }
    catch(Exception $e){
      print("Enter room failed: $e");
    }
    $room_name = str_replace("_room", "", $name);
}
}
//if not exists
try{
  $control = mysqli_query($conn,"SELECT id FROM chat_online WHERE username='$my_username' AND room_name = '$name'");
  if(mysqli_num_rows($control)>=2){
    mysqli_query($conn,"DELETE FROM chat_online WHERE username='$my_username' AND room_name = '$name' LIMIT 1");
  }
}
catch(Exception $e){}

$online_count_query = mysqli_query($conn,"SELECT COUNT(id) FROM chat_online WHERE room_name='$name'");
$online_count = mysqli_fetch_row($online_count_query);
$online_count = $online_count[0];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?=$room_name?></title>
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
            var scroll = document.getElementById('#messages');
            scroll.scrollTop = scroll.scrollHeight;
            scroll.animate({scrollTop: scroll.scrollHeight});
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
    <?php 
    if(isset($_REQUEST['leave_room'])){
      try{
        $leave_query = mysqli_query($conn,"DELETE FROM chat_online WHERE username='$my_username'");
        header("Location:rooms.php");
      }
      catch(Exception $e){
        print("Leave failed : $e");
      }
    }
    ?>
    <div class="row">
        <div class="col-lg-12">
          <div class="info-box">
            <div class="box box-warning direct-chat direct-chat-warning">
              <div class="box-header with-border">
              <form  method="POST">
                <h3 class="box-title text-white"><button class="btn btn-outline ti ti-arrow-left" type='submit' name="leave_room"> </button>  &nbsp; Online User: <?=$online_count?></h3>
              </form>
              </div>
              <div  class="box-body" id="scrl"> 
              <div class='direct-chat-messages' id="messages">
               
                <!-- Conversations are loaded here -->
                <?php
                    try{
                        $get_message = mysqli_query($conn,"SELECT * FROM $name ORDER BY id DESC");
                        $msg_count = mysqli_num_rows($get_message);
                        if($msg_count!=0){
                        while($messages = mysqli_fetch_array($get_message)){
                            $username = $messages['username'];
                            $message = $messages['message'];
                            $date = $messages['date'];
                            
                            if($username!=$my_username){
                                $get_pp = mysqli_query($conn,"SELECT pp FROM users WHERE username='$username'");
                                $count = mysqli_num_rows($get_pp);
                                if($count!=0){
                                   $satir = mysqli_fetch_row($get_pp);
                                   $pp = $satir[0]; 
                                   if($pp==null){
                                    $pp = "dist/img/img2.jpg";
                                   }  
                                }
                                echo " 
                                <div class='direct-chat-msg'>
                                  <div class='direct-chat-info clearfix'> <span class='direct-chat-name pull-left'><a href='user_detail.php?username=$username'>$username</a></span> <span class='direct-chat-timestamp pull-right'>$date</span> </div>
                                  <img class='direct-chat-img' src='$pp' alt='user image'>
                                  <div class='direct-chat-text'> $message </div>
                                </div>";
                            }
                            else{
                                echo " <div class='direct-chat-msg right'>
                                <div class='direct-chat-info clearfix'> <span class='direct-chat-name pull-right'>$my_username</span> <span class='direct-chat-timestamp pull-left'>$date</span> </div>
                                <img class='direct-chat-img' src='$my_pp' alt='user image'> 
                                <!-- /.direct-chat-img -->
                                <div class='direct-chat-text'> $message </div>
                              </div>";
                            }
                        }
                        
                      }
                     else{
                         echo "<div class='text-center h1'><br>MessageBox is Empty<br><br></div>";
                     }
                    }
                    catch(Exception $e){
                        echo "Error: ".$e;
                    }
                    
                ?>
                </div>
              </div>
             
              <div class="box-footer">
              <?php
                 if (isset($_POST['send']))
                 {
                    $msg = trim($_POST['message']);
                    $msg = strip_tags($_POST['message']);
                    $msg = htmlspecialchars($_POST['message']);
                    $nowDate = date("d.m.Y H:i:s"); 
                    try{
                        $send_msg = mysqli_query($conn,"INSERT INTO $name(username,message,date) VALUES ('$my_username','$msg','$nowDate')");
                    }
                    catch(Exception $e){
                      echo "Message send failed!";   
                    }
                 }
              ?>
                 <form method="POST"  accept-charset="UTF-8" enctype="multipart/form-data">
                  <div class="input-group">
                    <input name="message" placeholder="Type Message ..." class="form-control" type="text">
                    <span class="input-group-btn">
                    <button  type="submit"  name="send" class="btn btn-warning btn-flat">Send</button>
                    </span> </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        </div>
    </div>
    </div>
    <!-- /.content --> 

  <!-- /.content-wrapper -->
  <?php require('includes/footer.php'); ?>
</div>
<input id="deger" value="<?php echo $name; ?>" hidden>
<!-- ./wrapper --> 
                    
<!-- jQuery 3 --> 
<script src="dist/js/jquery.min.js"></script> 

<!-- v4.0.0-alpha.6 --> 
<script src="dist/bootstrap/js/bootstrap.min.js"></script> 

<!-- template --> 
<script src="dist/js/niche.js"></script>
<!-- Refresh Message -->

<script>  
 var url = "refresh_msg.php?name="+$("#deger").val();
 setInterval(function () {$("#messages").load(url)},3000);  
</script> 
</body>
</html>