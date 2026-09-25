<?php
header('Content-Type: application/json');
require('includes/auth/auth_functions.php');
require('includes/utf8/utf8_converter.php');
require('includes/user/user_functions.php');
require('../../includes/db_connect.php');

$headers = apache_request_headers();
if (isset($headers['Authorization'])) {
    $token = $headers['Authorization'];
    $result = tokenLoginControl($conn, $token);
    if ($result != null) {
        $myID = $result['id'];
        $following = getFollowing($conn, $myID);
        echo json_encode(array("status" => "success", "data" => utf8ize($following)));
    } else {
        echo json_encode(array("status" => "error", "data" => "Authorization error!"));
    }
} else {
    echo json_encode(array("status" => "error", 'data' => 'Authorization error!'));
}
$conn->close();
