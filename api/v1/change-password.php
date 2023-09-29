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
    if (isset($data['new_password'])) { //kullanıcı id var mı?
        $token = $headers['Authorization']; //kullanıcının giriş tokeni
        $new_password = $data['new_password']; // kullanıcının  yeni şifresi
        $result =  tokenLoginControl($conn,$token); //login kontrolü
    
        if($result != null){ //kullanıcı giriş yapmışsa
            $statsResponse = changePassword($conn,$new_password, $result['id']);
          
            if ($statsResponse != null) {
                echo json_encode(array("status" => "success","data"=>"Password changed!"));
            }
            else{
                echo json_encode(array("status" => "error","data"=>"Password change error!"));
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
