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
            $friend_delete_stmt = $conn->prepare("DELETE FROM friends WHERE (userid1=? AND userid2=?) OR (userid1=? AND userid2=?)");
            $friend_delete_stmt->bind_param("iiii", $my_id, $friend_id, $friend_id, $my_id);
            $friend_delete = $friend_delete_stmt->execute();
            $friend_delete_stmt->close();
            if(isset($friend_delete)){
                $request_save = $conn->prepare("INSERT INTO blockeduser (userid1,userid2) VALUES (?,?)");
                $request_save->bind_param("ii", $my_id, $friend_id);
            if ($request_save->execute()){
                header("Location:index.php");
                exit();
            }
            else{
                header("Location:index.php");
                exit();
            }
            }
            
          
?>