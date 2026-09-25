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

            $sonuc1_stmt = $conn->prepare("SELECT username FROM users WHERE id= ?");
            $sonuc1_stmt->bind_param("i", $friend_id);
            $sonuc1_stmt->execute();
            $sonuc1 = $sonuc1_stmt->get_result();
            while($satir1=mysqli_fetch_array($sonuc1))
            {
            $friend_username=$satir1['username'];
            }
            $sonuc1_stmt->close();

            $request_save = $conn->prepare("INSERT INTO friend_request (sender,receiver) VALUES (?,?)");
            $request_save->bind_param("ii", $my_id, $friend_id);
            if ($request_save->execute()){
            $info_text = "Awaiting Response";
                header("Location:user_detail.php?username=$friend_username");
                exit();
            }
            else{
                header("Location:user_detail.php?username=$friend_username");
                exit();
            }
          
?>