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
            $request_id = null;

            $sonuc_stmt = $conn->prepare("SELECT * FROM friends WHERE (userid1=? AND userid2=?) OR (userid1=? AND userid2=?)");
            $sonuc_stmt->bind_param("iiii", $my_id, $friend_id, $friend_id, $my_id);
            $sonuc_stmt->execute();
            $sonuc = $sonuc_stmt->get_result();
            while($satir=mysqli_fetch_array($sonuc))
            {
            $request_id=$satir['id'];
            }
            $sonuc_stmt->close();

            $sonuc1_stmt = $conn->prepare("SELECT username FROM users WHERE id= ?");
            $sonuc1_stmt->bind_param("i", $friend_id);
            $sonuc1_stmt->execute();
            $sonuc1 = $sonuc1_stmt->get_result();
            while($satir1=mysqli_fetch_array($sonuc1))
            {
            $friend_username=$satir1['username'];
            }
            $sonuc1_stmt->close();

            if($request_id!=null){
            $delete_request = $conn->prepare("DELETE FROM friends WHERE id=?");
            $delete_request->bind_param("i", $request_id);
            if ($delete_request->execute()){
                header("Location:user_detail.php?username=$friend_username");
                exit();
            }
            }
            else{
                header("Location:user_detail.php?username=$friend_username");
                exit();
            }
         
          
?>