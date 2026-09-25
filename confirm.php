<?php
require('includes/db_connect.php');
if(isset($_GET['username'])&& isset($_GET['token'])){
    $username = $_GET['username'];
    $token = $_GET['token'];
    $stmt = $conn->prepare("SELECT users.username,tokens.token FROM users,tokens WHERE users.username = tokens.username AND tokens.type='confirm' AND (users.username=? AND tokens.token = ?)");
    if(!$stmt){
        header('Location:index.php');
        exit();
    }
    $stmt->bind_param("ss", $username, $token);
    $stmt->execute();
    $usr_query = $stmt->get_result();
    $usr_count = $usr_query->num_rows;
    $stmt->close();
    if($usr_count==0){
        header('Location:index.php');
        exit();
    }
    else{
        try{
            $stmt = $conn->prepare("UPDATE users SET status=1 WHERE username=?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("DELETE FROM tokens WHERE token=?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->close();

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
    exit();
}

?>