<?php
header('Content-Type: application/json');
require('includes/auth/auth_functions.php');
require('includes/diaries/diaries_functions.php');
require('../../includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['feedID'])) {
        $feedId = (int) $data['feedID'];

        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $token = $headers['Authorization'];
            $result = tokenLoginControl($conn, $token);
            if ($result != null) {
                $myID = $result['id'];
                $likeResponse = toggleFeedLike($conn, $feedId, $myID);
                if ($likeResponse['success']) {
                    echo json_encode(array("status" => "success", "data" => array("liked" => $likeResponse['liked'], "count" => $likeResponse['count'])));
                } else {
                    echo json_encode(array("status" => "error", "data" => $likeResponse['message']));
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
