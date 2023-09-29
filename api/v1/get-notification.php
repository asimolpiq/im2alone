<?php
header('Content-Type: application/json');
// register.php
// Gerekli dosyaları ve veritabanı bağlantısını dahil edin
require('includes/auth/auth_functions.php');
require('includes/utf8/utf8_converter.php');
require('includes/user/user_functions.php');
require('../../includes/db_connect.php');





// İstek POST isteği mi kontrol edin
$headers = apache_request_headers();
if (isset($headers['Authorization'])) {
    $token = $headers['Authorization'];
    $result =  tokenLoginControl($conn, $token);
    if ($result != null) {
        $user_id = $result['id'];
        $diary_response = getNotifications($conn, $user_id);
        if ($diary_response == null) {
            echo json_encode(array("status" => "error", "data" => "You don't have a friend request."));;
        }
        else{
            echo json_encode(array("status" => "success", "data" => utf8ize($diary_response)));
        }
      
    } else {
        echo json_encode(array("status" => "error", "error" => "Authorization error!"));
    }
} else if (json_last_error() !== JSON_ERROR_NONE) {

    echo json_encode(array("status" => "error", "message" => "JSON veri hatası."));
} else {

    echo json_encode(array('error' => 'Geçersiz Yöntem.'));
}
$conn->close();
