<?php
require("includes/db_connect.php");
session_start();
ob_start();
if(!isset($_SESSION['im2alone_user'])){
    header("Location:index.php");
}
$user = $_SESSION['im2alone_user'];
$username = $user['username'];
$online_query = "UPDATE users SET online='0' WHERE username='$username'";
if ($conn->query($online_query,) === TRUE) {
    session_destroy();
    header("Refresh: 0; url=index.php");
    ob_end_flush();
}
$chat_query = mysqli_query($conn,"SELECT * FROM chat_online WHERE username='$username'");
if(mysqli_num_rows($chat_query)!=0){
    try{
        $leave_query = mysqli_query($conn,"DELETE FROM chat_online WHERE username='$username'");
        header("Location:rooms.php");
      }
      catch(Exception $e){
        print("Leave failed : $e");
      }
}

?>