<?php
require('includes/db_connect.php');
require('includes/room_guard.php');
ob_start();
session_start();
if(!isset($_SESSION["im2alone_user"])){
  header("Location:index.php");
  exit();
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
      exit();
  } else {
      $name = $_GET["name"];
      if(!isValidRoomTable($conn, $name)){
        header("Location:rooms.php");
        exit();
      }
      $room_name = str_replace("_room", "", $name);
  }
  }

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
            else{
                $pp = "dist/img/img2.jpg"; //silinmis kullanici, onceki mesajin pp'si kalmasin
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
     echo "<div class='user-list-empty'><i class='ti ti-comments'></i><p>No messages yet.</p><span>Say hi — start the conversation!</span></div>";
 }
}
catch(Exception $e){
    echo "Error: ".$e;
}

?>