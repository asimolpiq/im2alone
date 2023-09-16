<?php
header('Content-Type: application/json');
require('includes/auth/auth_functions.php');
require('includes/utf8/utf8_converter.php');
require('../../includes/db_connect.php');

// API'ye gelen isteği kontrol ediyoruz
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Giriş isteği alınıyor
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data["username"]) && isset($data["password"])) {
        $username = $data["username"];
        $password = $data["password"];
        // Kullanıcıyı doğruluyoruz
        $user = loginControl($conn, $username, $password);

        if ($user != null) {
            // Başarılı giriş durumunda kullanıcı bilgileri ile yanıt veriyoruz
            echo json_encode(array("status" => "success", "data" => utf8ize($user)));
        } else {
            // Giriş başarısız olursa hata ile yanıt veriyoruz
            echo json_encode(array("status" => "error", "error" => "Kullanıcı adı veya şifre yanlış."));
        }
    } else {
        // Eksik parametreler için hata ile yanıt veriyoruz
        echo json_encode(array("status" => "error", "error" => "Eksik parametreler."));
    }
} else {
    // Desteklenmeyen istek türleri için hata ile yanıt veriyoruz
    echo json_encode(array("status" => "error", "error" => "Desteklenmeyen istek türü."));
}

// Hata ayıklama için ekleyeceğimiz kod satırı
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(array("status" => "error", "error" => "JSON veri hatası."));
}

// Veritabanı bağlantısını kapatıyoruz
$conn->close();


?>
