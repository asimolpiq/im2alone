<?php
            require('includes/db_connect.php');
            ob_start();
            session_start();
            if(!isset($_SESSION["im2alone_user"])){
            header("Location:index.php");
            }
            $my_id = $_GET['my_id'];
            $friend_id = $_GET['friend_id']; 

            $delete_request = "DELETE FROM blockeduser WHERE userid1='$my_id' AND userid2='$friend_id'";
            if ($conn->query($delete_request)){
                header("Location:social_settings.php");
            }
         
          
?>