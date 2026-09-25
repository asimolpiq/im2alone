<?php
require('includes/class.upload.php');
require('includes/csrf.php');
ob_start();
session_start();
ini_set('display_errors', '0'); //class.upload is old, dont let php notices break the json
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

if (!isset($_FILES['diary_image']) || is_array($_FILES['diary_image']['name'])
    || $_FILES['diary_image']['name'] == "" || $_FILES['diary_image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(array("success" => false, "message" => "Select a picture dude."));
    exit();
}

//real image check BEFORE the upload class touches it. class.upload lets unknown
//image/* mimes (svg, ico...) through as raw copy, thats a stored xss hole
$img_info = @getimagesize($_FILES['diary_image']['tmp_name']);
$safe_types = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP, IMAGETYPE_BMP);
if ($img_info === false || !in_array($img_info[2], $safe_types, true)) {
    echo json_encode(array("success" => false, "message" => "Only jpg, png, gif or webp please."));
    exit();
}
//decompression bomb guard: a tiny png can claim 25000x25000 and eat all the ram
if ($img_info[0] * $img_info[1] > 25000000) {
    echo json_encode(array("success" => false, "message" => "Image is too big dude."));
    exit();
}

//simple rate limit, 20 uploads per hour is more than enough for a diary
if (!isset($_SESSION['diary_upload_times'])) {
    $_SESSION['diary_upload_times'] = array();
}
$_SESSION['diary_upload_times'] = array_values(array_filter($_SESSION['diary_upload_times'], function ($t) {
    return $t > time() - 3600;
}));
if (count($_SESSION['diary_upload_times']) >= 20) {
    echo json_encode(array("success" => false, "message" => "Too many uploads, slow down dude."));
    exit();
}

$user_id = (int) $im2alone_user['id'];
$image = $_FILES['diary_image'];
$foo = new Upload($image);
if ($foo->uploaded) {
    $foo->allowed = array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png', 'image/gif', 'image/webp', 'image/x-webp', 'image/bmp', 'image/x-ms-bmp');
    $foo->file_max_size = 5 * 1024 * 1024; //5mb enough dude
    $foo->file_new_name_body = "diary" . $user_id . "_" . bin2hex(random_bytes(8));
    $foo->image_resize = true;
    $foo->image_ratio = true;
    $foo->image_no_enlarging = true;
    $foo->image_x = 1200;
    $foo->image_y = 1200;
    //re-encode everything, kills hidden payloads. webp compresses way better than jpg,
    //fall back to jpg if the server gd doesnt support it
    if (function_exists('imagewebp')) {
        $foo->image_convert = 'webp';
        $foo->webp_quality = 80;
    } else {
        $foo->image_convert = 'jpg';
        $foo->jpeg_quality = 75;
    }
    $foo->image_default_color = '#ffffff';
    $foo->process('dist/diary_images/');
    if ($foo->processed) {
        $img_file_name = $foo->file_dst_name;
        $foo->clean();
        //belt and braces: if the convert somehow didnt run, dont serve the file
        $ext = strtolower(pathinfo($img_file_name, PATHINFO_EXTENSION));
        if ($ext != 'webp' && $ext != 'jpg') {
            @unlink('dist/diary_images/' . $img_file_name);
            echo json_encode(array("success" => false, "message" => "Upload failed. Only image files please."));
            exit();
        }
        $_SESSION['diary_upload_times'][] = time();
        echo json_encode(array("success" => true, "url" => "dist/diary_images/" . $img_file_name));
    } else {
        echo json_encode(array("success" => false, "message" => "Upload failed. Only image files please."));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Upload failed. " . $foo->error));
}
?>
