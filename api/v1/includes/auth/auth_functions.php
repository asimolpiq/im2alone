<?php
require_once(__DIR__ . '/../../../../includes/age_functions.php'); //13 yas kontrolu (web ile ortak)

function userAllreadyRegister($conn, $email,$username){
    $stmt = $conn->prepare("SELECT email FROM users WHERE email=? OR username = ?");
    if (!$stmt) {
      return true; // fail closed: don't let a broken check let a duplicate through
    }
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->num_rows;
    $stmt->close();
    if ($count != 0) {
      return true;
    }
    else{
      return false;
    }
  }

  function tokenExists($conn, $username, $type = 'recovery'){
    try{
      $stmt = $conn->prepare("SELECT * FROM tokens WHERE username=? AND type=?");
      $stmt->bind_param("ss", $username, $type);
      $stmt->execute();
      $token_exists = $stmt->get_result();
      $count = $token_exists->num_rows;
      $stmt->close();
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

  function tokenLoginControl($conn,$token){
    if($token === "" || $token === null){
      return null;
    }
    $stmt = $conn->prepare("SELECT * FROM users WHERE token = ?");
    if (!$stmt) {
      return null;
    }
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $response = $stmt->get_result();
    $stmt->close();
    if($response->num_rows===1){
      $user =  $response->fetch_assoc();
      if(isset($user['is_banned']) && $user['is_banned'] == 1){
        return null;
      }
      //13 yas alti hicbir sosyal endpoint'e erisemez (token gecersiz sayilir)
      if(isUnderMinAge($user['birthday'])){
        return null;
      }
      if($user['pp'] == ""){
        $user['pp'] = null;
      }
        return $user;
    }
    else{
      return null;
    }

  }


  function loginControl($conn, $username, $password) {
    // Gelen şifreyi MD5 ile hash'leme işlemi
    $hashed_password = md5(md5($password));

    // Kullanıcı adını ve hashlenmiş şifreyi veritabanında sorgulayarak kontrol ediyoruz
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    if (!$stmt) {
      return null;
    }
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if(isset($user['is_banned']) && $user['is_banned'] == 1){
          return array("banned" => true);
        }
        //13 yas alti giris yapamaz (App Store yas siniflandirmasi)
        if(isUnderMinAge($user['birthday'])){
          return array("underage" => true);
        }
        $token= bin2hex(random_bytes(16));
        // Giriş başarılı
        $user['token'] = $token;
        if($user['pp'] == ""){
          $user['pp'] = null;
        }
        // Giriş tarihini ve IP adresini güncelleme
        $userid = (int) $user['id'];
        $ip_adresi = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";
        $currentdate = date("Y/m/d");

        $update = $conn->prepare("UPDATE users SET online = '1', token = ? WHERE id = ?");
        $update->bind_param("si", $token, $userid);
        $update->execute();
        $update->close();

        //log satiri hic acilmamis olabilir, yoksa ac (UPDATE 0 satiri etkiliyordu)
        $log_check = $conn->prepare("SELECT id FROM log WHERE userid = ?");
        $log_check->bind_param("i", $userid);
        $log_check->execute();
        $log_exists = $log_check->get_result()->num_rows > 0;
        $log_check->close();
        if ($log_exists) {
          $update = $conn->prepare("UPDATE log SET date = ?, ip = ? WHERE userid = ?");
          $update->bind_param("ssi", $currentdate, $ip_adresi, $userid);
        } else {
          $update = $conn->prepare("INSERT INTO log (userid,date,ip) VALUES (?,?,?)");
          $update->bind_param("iss", $userid, $currentdate, $ip_adresi);
        }
        $update->execute();
        $update->close();

        return $user;
    }

    return null; // Giriş başarısız
}
?>