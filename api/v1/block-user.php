<?php
header('Content-Type: application/json');
require('includes/auth/auth_functions.php');
require('includes/utf8/utf8_converter.php');
require('includes/user/user_functions.php');
require('../../includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['userID'])) {
        $targetID = trim($data['userID']);
        $targetID = strip_tags($targetID);
        $targetID = htmlspecialchars($targetID);

        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $token = $headers['Authorization'];
            $result = tokenLoginControl($conn, $token);
            if ($result != null) {
                $myID = $result['id'];
                $blockResponse = blockUser($conn, $myID, $targetID);
                if ($blockResponse) {
                    echo json_encode(array("status" => "success", "data" => "Kullanıcı engellendi."));
                } else {
                    echo json_encode(array("status" => "error", "data" => "Kullanıcı engellenemedi."));
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
