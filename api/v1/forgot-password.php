<?php
header('Content-Type: application/json');
// register.php
// Gerekli dosyaları ve veritabanı bağlantısını dahil edin
require('includes/auth/auth_functions.php');
require('includes/utf8/utf8_converter.php');
require('../../includes/db_connect.php');

// İstek POST isteği mi kontrol edin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true); 
  if(isset($data['username']) && isset($data['email'])){
  // İstekten verileri alın

  $username = trim($data['username']);
  $username = strip_tags($data['username']);
  $username = htmlspecialchars($data['username']);



  $email = trim($data['email']);
  $email = strip_tags($data['email']);
  $email = htmlspecialchars($data['email']);
 
  $isUserExists = userAllreadyRegister($conn,$email,$username); // kullanıcı kayıtlı mı?

  if ($isUserExists) { //kayıtlıysa
   $isTokenExists = tokenExists($conn,$username);

  

   if(!$isTokenExists){
    $token= bin2hex(random_bytes(16));
    try{
        mysqli_query($conn,"INSERT INTO tokens(username,token) VALUES ('$username','$token')");
        echo json_encode(array("status" => "success","data"=>"Şifre yenileme mailiniz gönderildi"));
    }
    catch(Exception $e){
        echo $e;
    }
   
   }
   else{

    echo json_encode(array("status" => "error","data"=>"Zaten yenileme talebiniz mevcut"));
   }
   
  } else {
    //kullanıcı kayıtlı değilse 
    echo json_encode(array("status" => "error",'data' => 'Kullanıcı kayıtlı değil.'));
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
  echo json_encode(array("status" => "error", 'data' => 'Geçersiz Yöntem.'));
 
}
$conn->close();



?>
