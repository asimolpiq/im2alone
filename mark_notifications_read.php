<?php
require('includes/db_connect.php');
require_once('includes/csrf.php');
ob_start();
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION["im2alone_user"])) {
    echo json_encode(array("success" => false, "message" => "Please login."));
    exit();
} else {
    $im2alone_user = $_SESSION["im2alone_user"];
}

if (!isset($_POST['csrf_token']) || !csrfTokenValid($_POST['csrf_token'])) {
    echo json_encode(array("success" => false, "message" => "Invalid request."));
    exit();
}

$my_id = (int) $im2alone_user['id'];
if (mysqli_query($conn, "UPDATE friend_request SET is_read=1 WHERE receiver='$my_id'")) {
    echo json_encode(array("success" => true));
} else {
    echo json_encode(array("success" => false, "message" => "Update failed."));
}
?>
