<?php
// register.php
// Gerekli dosyaları ve veritabanı bağlantısını dahil edin
require('includes/auth/auth_functions.php');
require('includes/utf8/utf8_converter.php');
require('../../includes/db_connect.php');
header('Content-Type: application/json');
// İstek POST isteği mi kontrol edin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true); 
  if(isset($data['username']) && isset($data['realname']) && isset($data['email']) && isset($data['password'])  && isset($data['birthday'])&& isset($data['gender'])){
  // İstekten verileri alın

  $username = trim($data['username']);
  $username = strip_tags($data['username']);
  $username = htmlspecialchars($data['username']);

  $realname = trim($data['realname']);
  $realname = strip_tags($data['realname']);
  $realname = htmlspecialchars($data['realname']);

  $email = trim($data['email']);
  $email = strip_tags($data['email']);
  $email = htmlspecialchars($data['email']);
 
  $gender = $data['gender'];

  $recive_password = trim($data['password']);
  $recive_password = strip_tags($data['password']);
  $recive_password = htmlspecialchars($data['password']);
  $password = md5($recive_password);
  $password = md5($password);


  $birthday = date('d/m/Y', strtotime($data['birthday']));

  
  $error = userAllreadyRegister($conn,$email,$username);



  if (!$error) {
    $tokenLength = 32; // Örnek olarak, 32 karakterlik bir token oluşturuyoruz.
    $token = base64_encode(random_bytes($tokenLength)); 
    try{
      $user_save = "INSERT INTO users (username,realname,password,email,gender,birthday,permission,status,token) 
      VALUES ('$username','$realname','$password','$email','$gender','$birthday',0,0,'$token')";
          if ($conn->query($user_save)) { 
            echo json_encode(array("status" => "success",'token' => $token));
          } else {
            echo json_encode(array("status" => "error",'data' => 'Kayıt Başarısız oldu!'));
          }
    }
    catch(Exception $e){
        echo json_encode(array("status" => "error",'data' =>$e));
    }
   
  } else {
    // Doğrulama başarısız olursa, hata mesajı ile yanıt verin

    echo json_encode(array("status" => "error",'data' => 'Kullanıcı Zaten Kayıtlı.'));
  }
} else {
   // Eksik parametreler için hata ile yanıt veriyoruz
   echo json_encode(array("status" => "error", "data" => "Eksik parametreler."));
  
}
// Veritabanı bağlantısını kapatıyoruz

//Kullanıcı kayıtlı mı?
}
else if (json_last_error() !== JSON_ERROR_NONE) {
 
  echo json_encode(array("status" => "error", "data" => "JSON veri hatası."));
}
else{
 
  echo json_encode(array("status" => "error",'data' => 'Geçersiz Yöntem.'));
 
}
$conn->close();



?>
