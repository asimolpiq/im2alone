<?php
            require('includes/db_connect.php');
            ob_start();
            session_start();
            if(!isset($_SESSION["im2alone_user"])){
            header("Location:index.php");
            }
            $my_id = $_GET['my_id'];
            $friend_id = $_GET['friend_id']; 

            $sonuc1=mysqli_query($conn,"SELECT username FROM users WHERE id= '$friend_id'");
            while($satir1=mysqli_fetch_array($sonuc1))
            {
            $friend_username=$satir1['username'];
            }
           
            $request_save = "INSERT INTO friend_request (sender,receiver) VALUES ('$my_id','$friend_id')";
            if ($conn->query($request_save)){
            $info_text = "Awaiting Response";
                header("Location:user_detail.php?username=$friend_username");
            }
            else{
                header("Location:user_detail.php?username=$friend_username");
            }
          
?>