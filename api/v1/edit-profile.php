<?php
header('Content-Type: application/json');
// register.php
// Gerekli dosyaları ve veritabanı bağlantısını dahil edin
require('includes/user/user_functions.php');
require('includes/auth/auth_functions.php');
require('includes/utf8/utf8_converter.php');
require('../../includes/db_connect.php');





// İstek POST isteği mi kontrol edin
$headers = apache_request_headers();
if( $_SERVER['REQUEST_METHOD'] === 'POST'){
    $data = json_decode(file_get_contents('php://input'), true); 
if(isset($headers['Authorization'])){
    if (isset($data['username']) && isset($data['fullname']) && isset($data['bio'])) { //kullanıcı id var mı?
        $token = $headers['Authorization']; //kullanıcının giriş tokeni
        $username = $data['username']; // kullanıcının  username'i
        $fullname = $data['fullname']; // kullanıcının  fullname'i
        $bio = $data['bio']; // kullanıcının  bio'su
        $result =  tokenLoginControl($conn,$token); //login kontrolü
    
        if($result != null){ //kullanıcı giriş yapmışsa
            $statsResponse = editProfile($conn,$username, $fullname, $bio, $result['id']);
          
            if ($statsResponse != null) {
                echo json_encode(array("status" => "success","data"=>"Profile edited!"));
            }
            else{
                echo json_encode(array("status" => "error","data"=>"Profile edit error!"));
            }
        }
        else{ 
            echo json_encode(array("status" => "error","error"=>"Authorization error!"));
        }
       
       }
       else{
        echo json_encode(array("status" => "error", "data" => "Eksik parametreler."));
    }
}
else{
    echo json_encode(array("status" => "error","data"=>"Authorization error!"));
}

}
else if (json_last_error() !== JSON_ERROR_NONE) {
  echo json_encode(array("status" => "error", "data" => "JSON veri hatası."));
}
else{
  echo json_encode(array("status" => "error", 'data' => 'Geçersiz Yöntem.'));
 
}
$conn->close();



?>
