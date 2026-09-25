<?php
header('Content-Type: application/json');
// delete-account.php
// Gerekli dosyaları ve veritabanı bağlantısını dahil edin
require('includes/auth/auth_functions.php');
require('includes/utf8/utf8_converter.php');
require('../../includes/db_connect.php');
require('../../includes/account_delete.php');

// İstek POST isteği mi kontrol edin
$headers = apache_request_headers();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($headers['Authorization'])) {
        if (isset($data['password'])) { //hesap silme sifre onayi ister
            $token = $headers['Authorization']; //kullanıcının giriş tokeni

            $result = tokenLoginControl($conn, $token); //login kontrolü

            if ($result != null) { //kullanıcı giriş yapmışsa
                $password = md5(md5(trim($data['password'])));
                if ($password === $result['password']) {
                    $delete_response = deleteUserAccount($conn, (int) $result['id']);
                    if ($delete_response) {
                        echo json_encode(array("status" => "success", "data" => "Hesabınız ve tüm verileriniz silindi."));
                    } else {
                        echo json_encode(array("status" => "error", "data" => "Silme işlemi sırasında bir hata oluştu!"));
                    }
                } else {
                    echo json_encode(array("status" => "error", "data" => "Şifre yanlış, hesap silinmedi."));
                }
            } else {
                echo json_encode(array("status" => "error", "error" => "Authorization error!"));
            }
        } else {
            echo json_encode(array("status" => "error", "data" => "Eksik parametreler."));
        }
    } else {
        echo json_encode(array("status" => "error", "data" => "Authorization error!"));
    }
} else if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(array("status" => "error", "data" => "JSON veri hatası."));
} else {
    echo json_encode(array("status" => "error", 'data' => 'Geçersiz Yöntem.'));
}
$conn->close();
?>
