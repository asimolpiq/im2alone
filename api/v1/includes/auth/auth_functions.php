<?php 
function userAllreadyRegister($conn, $email,$username){
    $query = "SELECT email FROM users WHERE email='$email' OR username = '$username'";
    $result = mysqli_query($conn, $query);
    $count = mysqli_num_rows($result);
    if ($count != 0) {
      return true;
    }
    else{
      return false;
    }
  }

  function tokenExists($conn, $username){
    try{
      $token_exists = mysqli_query($conn,"SELECT * FROM tokens WHERE username='$username'");
      $count = mysqli_num_rows($token_exists);
       if ($count != 0) {
          return true;
         }
       else{
         return false;
        }
  }
  catch(Exception $e){
    return false;
  }
  }
  function getUser($conn,$email){
    $response = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
    $count = mysqli_num_rows($response);
    if($count>0){
      echo $response->fetch_assoc();
        return $response->fetch_assoc();
    }
    else{
        return null;
    }

  }

  function tokenLoginControl($conn,$token){
    $response = $conn->query("SELECT * FROM users WHERE token = '$token'");
    if($response->num_rows===1){
      return $response->fetch_assoc();
    }
    else{
      return null;
    }

  }


  function loginControl($conn, $username, $password) {
    // Gelen şifreyi MD5 ile hash'leme işlemi
    $hashed_password = md5(md5($password));

    // Kullanıcı adını ve hashlenmiş şifreyi veritabanında sorgulayarak kontrol ediyoruz
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
    $result = $conn->query($query);
    
    if ($result->num_rows === 1) {
      $token= bin2hex(random_bytes(16));
        // Giriş başarılı
        $user = $result->fetch_assoc();
        $user['token'] = $token;
        if($user['pp'] == ""){
          $user['pp'] = "empty";
        }
        // Giriş tarihini ve IP adresini güncelleme
        $userid = $user['id'];
        $ip_adresi = "";
        $currentdate = date("Y/m/d");

        $update_query = "UPDATE users SET online = '1' WHERE username = '$username'";
        $conn->query($update_query);
        $update_query = "UPDATE users SET token = '$token' WHERE username = '$username'";
        $conn->query($update_query);

        $update_query = "UPDATE log SET date = '$currentdate', ip = '$ip_adresi' WHERE userid = '$userid'";
        $conn->query($update_query);

        return $user;
    }

    return null; // Giriş başarısız
}
?>