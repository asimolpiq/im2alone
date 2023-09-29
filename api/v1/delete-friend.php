<?php
header('Content-Type: application/json');
// register.php
// Gerekli dosyaları ve veritabanı bağlantısını dahil edin
require('includes/auth/auth_functions.php');
require('includes/utf8/utf8_converter.php');
require('includes/user/user_functions.php');
require('../../includes/db_connect.php');

// İstek POST isteği mi kontrol edin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['friendID'])) {
        $friendID = $data['friendID'];
        $friendID = trim($friendID);
        $friendID = strip_tags($friendID);
        $friendID = htmlspecialchars($friendID);

        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $token = $headers['Authorization'];
            $result =  tokenLoginControl($conn, $token);
            if ($result != null) {
                $myID = $result['id'];
                $searchResponse = deleteFriend($conn, $myID, $friendID);
                if ($searchResponse) {
                    echo json_encode(array("status" => "success", "data" => "Arkadaş Silindi."));
                } else {
                    echo json_encode(array("status" => "success", "data" => "Arkadaşlık Silinemedi."));
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
    } else {
        echo json_encode(array("status" => "error", "error" => "Eksik parametreler!"));
    }
} else {

    echo json_encode(array('error' => 'Geçersiz Yöntem.'));
}
