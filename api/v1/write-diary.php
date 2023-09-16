<?php
header('Content-Type: application/json');
// Gerekli dosyaları ve veritabanı bağlantısını dahil edin
require('includes/auth/auth_functions.php');
require('includes/utf8/utf8_converter.php');
require('includes/diaries/diaries_functions.php');
require('../../includes/db_connect.php');

// İstek POST isteği mi kontrol edin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['content']) && isset($data['link']) && isset($data['privacy'])) {
        // İstekten verileri alın

        $content = trim($data['content']);
        $content = strip_tags($data['content']);
        $content = htmlspecialchars($data['content']);

        $link = trim($data['link']);

        $privacy = trim($data['privacy']);
        $privacy = intval($privacy);

       
    } else {
        echo json_encode(array("status" => "error", "data" => "Eksik parametreler."));
        die();
    }

    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
        $token = $headers['Authorization'];
        $result =  tokenLoginControl($conn, $token);
        if ($result != null) {
            $user_id = $result['id'];
            $diary_response = writeDiary($conn, $user_id, $content, $link, $privacy);
            
            if ($diary_response) {
                echo json_encode(array("status" => "success", "data" => "Diary created!"));
            } else {
                echo json_encode(array("status" => "error", "data" => "Diary not created!"));
            }
        } else {
            echo json_encode(array("status" => "error", "data" => "unauthorized"));
        }
    }
} else if (json_last_error() !== JSON_ERROR_NONE) {
  
    echo json_encode(array("status" => "error", "data" => "JSON veri hatası."));
} else {
  
    echo json_encode(array("status" => "error",'data' => 'Geçersiz Yöntem.'));
}

$conn->close();
