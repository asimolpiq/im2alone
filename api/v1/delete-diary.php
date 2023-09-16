<?php
header('Content-Type: application/json');
// register.php
// Gerekli dosyaları ve veritabanı bağlantısını dahil edin
require('includes/auth/auth_functions.php');
require('includes/utf8/utf8_converter.php');
require('includes/diaries/diaries_functions.php');
require('../../includes/db_connect.php');





// İstek POST isteği mi kontrol edin
$headers = apache_request_headers();
if( $_SERVER['REQUEST_METHOD'] === 'POST'){
    $data = json_decode(file_get_contents('php://input'), true); 
if(isset($headers['authorization'])){
    if (isset($data['id'])) { //diary id var mı?
        $token = $headers['authorization']; //kullanıcının giriş tokeni

        $diary_id = $data['id']; //kullanıcının günlüğünün idsi
    
        $result =  tokenLoginControl($conn,$token); //login kontrolü
    
        if($result != null){ //kullanıcı giriş yapmışsa
            $delete_response = deleteDiary($conn,$diary_id); 
    
            if ($delete_response ) {
                echo json_encode(array("status" => "success","data"=>"Başarıyla silindi!"));
            }
            else{
                echo json_encode(array("status" => "error","data"=>"Silme işlemi sırasında bir hata oluştu!"));
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
