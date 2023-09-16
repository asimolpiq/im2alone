<?php
// register.php
// Gerekli dosyaları ve veritabanı bağlantısını dahil edin
header('Content-Type: application/json');
require('includes/auth/auth_functions.php');
require('includes/utf8/utf8_converter.php');
require('includes/general/general_functions.php');
require('../../includes/db_connect.php');





// İstek POST isteği mi kontrol edin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['searchText'])) {
        $search_text = $data['searchText'];
        $search_text = trim($search_text);
        $search_text = strip_tags($search_text);
        $search_text = htmlspecialchars($search_text);

        $headers = apache_request_headers();
        if (isset($headers['authorization'])) {
            $token = $headers['authorization'];
            $result =  tokenLoginControl($conn, $token);
            if ($result != null) {
                $searchResponse = getUserSearch($conn, $search_text);
                if (!isset($searchResponse['error'])) {
                    echo json_encode(array("status" => "success", "data" => utf8ize($searchResponse)));
                } else {
                    echo json_encode(array("status" => "error", "data" =>"User not found!"));
                }
            } else {
                echo json_encode(array("status" => "error", "error" => "Authorization error!"));
            }
        } else if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(array("status" => "error", "message" => "JSON veri hatası."));
        } else {
            echo json_encode(array('error' => 'Authorization error!'));
        }
        $conn->close();
    } else {
        echo json_encode(array("status" => "error", "error" => "Eksik parametreler!"));
    }
} else {

    echo json_encode(array('error' => 'Geçersiz Yöntem.'));
}
