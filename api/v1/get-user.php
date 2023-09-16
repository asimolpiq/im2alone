<?php
header('Content-Type: application/json');
require('includes/auth/auth_functions.php');
require('includes/utf8/utf8_converter.php');
require('../../includes/db_connect.php');
header('Content-Type: application/json');

// API'ye gelen isteği kontrol ediyoruz
$headers = apache_request_headers();

if (isset($headers['Authorization'])) {
    $token = $headers['Authorization'];
    $result =  tokenLoginControl($conn,$token);

        if ($result != null) {
            // Başarılı giriş durumunda kullanıcı bilgileri ile yanıt veriyoruz
            echo json_encode(array("status" => "success", "data" => utf8ize($result)));
        } else {
            // Giriş başarısız olursa hata ile yanıt veriyoruz
            echo json_encode(array("status" => "error", "data" => "unauthorized"));
        }
   
} else {
   
    echo json_encode(array("status" => "error", "data" => "unauthorized"));
}

// Hata ayıklama için ekleyeceğimiz kod satırı
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(array("status" => "error", "data" => "JSON veri hatası."));
}

// Veritabanı bağlantısını kapatıyoruz
$conn->close();


?>
