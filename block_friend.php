<?php
            require('includes/db_connect.php');
            ob_start();
            session_start();
            if(!isset($_SESSION["im2alone_user"])){
            header("Location:index.php");
            }
            $my_id = $_GET['my_id'];
            $friend_id = $_GET['friend_id']; 
            $friend_delete = mysqli_query($conn,"DELETE FROM friends WHERE (userid1='$my_id' AND userid2='$friend_id') OR (userid1='$friend_id' AND userid2='$my_id')");
            if(isset($friend_delete)){
                $request_save = "INSERT INTO blockeduser (userid1,userid2) VALUES ('$my_id','$friend_id')";
            if ($conn->query($request_save)){
                header("Location:index.php");
            }
            else{
                header("Location:index.php");
            }
            }
            
          
?>