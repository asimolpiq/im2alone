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
if (isset($headers['Authorization'])) {
    $token = $headers['Authorization'];
    $result =  tokenLoginControl($conn, $token);
    if ($result != null) {
        $user_id = $result['id'];
        $diary_response = getAllFeed($conn, $user_id);
        echo json_encode(array("status" => "success", "data" => utf8ize($diary_response)));
    } else {
        echo json_encode(array("status" => "error", "data" => "Authorization error!"));
    }
} else if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(array("status" => "error", "data" => "JSON veri hatası."));
} else {
    echo json_encode(array("status" => "error", 'data' => 'Authorization error!'));
}
$conn->close();
