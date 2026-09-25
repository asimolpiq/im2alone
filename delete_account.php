<?php
require('includes/db_connect.php');
require_once('includes/csrf.php');
require('includes/account_delete.php');
ob_start();
session_start();
if (!isset($_SESSION["im2alone_user"])) {
    header("Location:index.php");
    exit();
} else {
    $im2alone_user = $_SESSION["im2alone_user"];
}

if (!isset($_POST['delete_account'])) {
    header("Location:profile-page.php");
    exit();
}

if (!isset($_POST['csrf_token']) || !csrfTokenValid($_POST['csrf_token'])) {
    $_SESSION['delete_error'] = "Session expired, please refresh the page and try again.";
    header("Location:profile-page.php");
    exit();
}

//password confirm, this is not something you do by accident
$recive_password = isset($_POST['password']) ? trim($_POST['password']) : "";
if ($recive_password == "") {
    $_SESSION['delete_error'] = "Please enter your password to delete your account.";
    header("Location:profile-page.php");
    exit();
}
$password = md5(md5($recive_password));
$my_id = (int) $im2alone_user['id'];

$check_stmt = $conn->prepare("SELECT id FROM users WHERE id=? AND password=?");
$check_stmt->bind_param("is", $my_id, $password);
$check_stmt->execute();
$match = $check_stmt->get_result()->num_rows > 0;
$check_stmt->close();
if (!$match) {
    $_SESSION['delete_error'] = "Wrong password, account was not deleted.";
    header("Location:profile-page.php");
    exit();
}

if (deleteUserAccount($conn, $my_id)) {
    session_unset();
    session_destroy();
    header("Location:index.php?deleted=1");
    exit();
} else {
    $_SESSION['delete_error'] = "Something went wrong, account was not deleted.";
    header("Location:profile-page.php");
    exit();
}
?>
