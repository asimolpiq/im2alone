<?php
            require('includes/db_connect.php');
            ob_start();
            session_start();
            if(!isset($_SESSION["im2alone_user"])){
            header("Location:index.php");
            }
            $my_id = $_GET['my_id'];
            $friend_id = $_GET['friend_id']; 
            
            $sonuc=mysqli_query($conn,"SELECT * FROM friends WHERE (userid1='$my_id' AND userid2='$friend_id') OR (userid1='$friend_id' AND userid2='$my_id')");
            while($satir=mysqli_fetch_array($sonuc))
            {
            $request_id=$satir['id'];
            }

            $sonuc1=mysqli_query($conn,"SELECT username FROM users WHERE id= '$friend_id'");
            while($satir1=mysqli_fetch_array($sonuc1))
            {
            $friend_username=$satir1['username'];
            }

            $delete_request = "DELETE FROM friends WHERE id='$request_id'";
            if ($conn->query($delete_request)){
                header("Location:user_detail.php?username=$friend_username");
            }
         
          
?>