<?php
require('includes/db_connect.php');
require_once('includes/csrf.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require('PHPMailer/SMTP.php');
require('PHPMailer/Exception.php');
require('PHPMailer/PHPMailer.php');
require('includes/mailer.php');
ob_start();
session_start();
ini_set('display_errors', '0');
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

//spam guard: one mail per 2 minutes
if (isset($_SESSION['last_verify_mail']) && time() - $_SESSION['last_verify_mail'] < 120) {
    echo json_encode(array("success" => false, "message" => "Please wait a bit before trying again."));
    exit();
}

$my_id = (int) $im2alone_user['id'];
$user_q = mysqli_query($conn, "SELECT username, email, status FROM users WHERE id='$my_id'");
$user_row = mysqli_fetch_assoc($user_q);
if (!$user_row) {
    echo json_encode(array("success" => false, "message" => "User not found."));
    exit();
}
if ((int) $user_row['status'] != 0) {
    echo json_encode(array("success" => false, "message" => "Your account is already confirmed."));
    exit();
}

$username = $user_row['username'];
$email = $user_row['email'];

//reuse the existing token if there is one, otherwise create a fresh one
$token_stmt = $conn->prepare("SELECT token FROM tokens WHERE username=? AND type='confirm'");
$token_stmt->bind_param("s", $username);
$token_stmt->execute();
$token_result = $token_stmt->get_result();
$token_stmt->close();
if ($token_row = $token_result->fetch_row()) {
    $token = $token_row[0];
} else {
    $token = bin2hex(random_bytes(16));
    $insert_stmt = $conn->prepare("INSERT INTO tokens(username,token,type) VALUES (?,?,'confirm')");
    $insert_stmt->bind_param("ss", $username, $token);
    $insert_stmt->execute();
    $insert_stmt->close();
}

if (sendConfirmationMail($email, $username, $token)) {
    $_SESSION['last_verify_mail'] = time();
    echo json_encode(array("success" => true, "message" => "Confirmation mail sent! Check your inbox."));
} else {
    echo json_encode(array("success" => false, "message" => "Mail could not be sent right now, please try again later."));
}
?>
