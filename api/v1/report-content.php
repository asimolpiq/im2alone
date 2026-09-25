<?php
header('Content-Type: application/json');
require('includes/auth/auth_functions.php');
require('includes/utf8/utf8_converter.php');
require('includes/moderation/moderation_functions.php');
require('../../includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['reportedUserID']) && isset($data['reason'])) {
        $reportedUserID = trim($data['reportedUserID']);
        $reportedUserID = strip_tags($reportedUserID);
        $reportedUserID = htmlspecialchars($reportedUserID);

        $reason = trim($data['reason']);
        $reason = strip_tags($reason);
        $reason = htmlspecialchars($reason);

        $description = isset($data['description']) ? trim($data['description']) : '';
        $description = strip_tags($description);
        $description = htmlspecialchars($description);

        $feedID = isset($data['feedID']) && $data['feedID'] !== null && $data['feedID'] !== ''
            ? (int) $data['feedID']
            : null;

        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $token = $headers['Authorization'];
            $result = tokenLoginControl($conn, $token);
            if ($result != null) {
                $myID = $result['id'];
                $reportResponse = createReport($conn, $myID, $reportedUserID, $feedID, $reason, $description);
                if ($reportResponse) {
                    echo json_encode(array("status" => "success", "data" => "Şikayetiniz alındı. En geç 24 saat içinde incelenecektir."));
                } else {
                    echo json_encode(array("status" => "error", "data" => "Şikayet gönderilemedi."));
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
