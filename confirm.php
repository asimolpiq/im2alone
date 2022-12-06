<?php
require('includes/db_connect.php');
if(isset($_GET['username'])&& isset($_GET['token'])){
    $username = $_GET['username'];
    $token = $_GET['token'];
    $usr_query = mysqli_query($conn,"SELECT users.username,tokens.token FROM users,tokens WHERE users.username = tokens.username AND (users.username='$username' AND tokens.token = '$token')");
    if(mysqli_num_rows($usr_query)==0){
        header('Location:index.php');
    }
    else{
        try{
            mysqli_query($conn,"UPDATE users SET status=1 WHERE username='$username'");
            mysqli_query($conn,"DELETE FROM tokens WHERE token='$token'");
            echo "İşlem tamam hocam";
            header('refresh:3; url=index.php');
        }
        catch(Exception $e){
            echo "$e";
        }
    }

}
else{
    header('Location:index.php');
}

?>