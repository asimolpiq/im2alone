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
    if (isset($data['username']) && isset($data['fullname']) && isset($data['bio']) && isset($data['email']) && isset($data['gender']) && isset($data['birthday'])) { //kullanıcı id var mı?
        $token = $headers['Authorization']; //kullanıcının giriş tokeni
        $username = trim($data['username']);
        $fullname = trim($data['fullname']);
        $bio = trim($data['bio']);
        $email = trim($data['email']);
        $gender = $data['gender'];
        $birthday = $data['birthday'];
        $result =  tokenLoginControl($conn,$token); //login kontrolü

        if($result != null){ //kullanıcı giriş yapmışsa
            $editResponse = editProfile($conn,$username, $fullname, $email, $gender, $birthday, $bio, $result['id']);

            if ($editResponse['success']) {
                echo json_encode(array("status" => "success","data"=>"Profile edited!"));
            }
            else{
                echo json_encode(array("status" => "error","data"=>$editResponse['message']));
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
