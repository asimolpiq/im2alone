<?php
            require('includes/db_connect.php');
            require('includes/csrf.php');
            ob_start();
            session_start();
            if(!isset($_SESSION["im2alone_user"])){
            header("Location:index.php");
            exit();
            }
            if(!isset($_GET['csrf']) || !csrfTokenValid($_GET['csrf'])){
            header("Location:index.php");
            exit();
            }
            $my_id = (int) $_SESSION["im2alone_user"]['id'];
            $friend_id = (int) $_GET['friend_id'];

            $delete_request = $conn->prepare("DELETE FROM blockeduser WHERE userid1=? AND userid2=?");
            $delete_request->bind_param("ii", $my_id, $friend_id);
            if ($delete_request->execute()){
                header("Location:social_settings.php");
                exit();
            }
         
          
?>