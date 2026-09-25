<?php
header('Content-Type: application/json');
require('includes/auth/auth_functions.php');
require('../../includes/class.upload.php');
require('../../includes/db_connect.php');

$headers = apache_request_headers();
if (!isset($headers['Authorization'])) {
    echo json_encode(array("status" => "error", "data" => "Authorization error!"));
    exit();
}
$token = $headers['Authorization'];
$result = tokenLoginControl($conn, $token);
if ($result == null) {
    echo json_encode(array("status" => "error", "data" => "Authorization error!"));
    exit();
}

if (!isset($_FILES['avatar']) || $_FILES['avatar']['name'] == "") {
    echo json_encode(array("status" => "error", "data" => "No file uploaded."));
    exit();
}

$image = $_FILES['avatar'];
//real image check, class.upload alone lets svg/ico style image/* mimes through raw
$img_info = @getimagesize($image['tmp_name']);
$safe_types = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP, IMAGETYPE_BMP);
if (is_array($image['name']) || $img_info === false || !in_array($img_info[2], $safe_types, true) || $img_info[0] * $img_info[1] > 25000000) {
    echo json_encode(array("status" => "error", "data" => "Only jpg, png, gif or webp please."));
    exit();
}

$username = $result['username'];
$old_pp = $result['pp'];

$foo = new Upload($image);
if (!$foo->uploaded) {
    echo json_encode(array("status" => "error", "data" => "Upload failed."));
    exit();
}
$foo->allowed = array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png', 'image/gif', 'image/webp', 'image/x-webp', 'image/bmp', 'image/x-ms-bmp');
$foo->file_max_size = 5 * 1024 * 1024;
$foo->image_convert = 'jpg'; //re-encode everything, kills hidden payloads
$foo->file_overwrite = true;
$foo->file_new_name_body = "'pp'+$username";
$foo->image_resize = true;
$foo->image_ratio_crop = true;
$foo->image_x = 600;
$foo->image_y = 500;
$foo->process('../../dist/profile_pictures/');

if (!$foo->processed) {
    echo json_encode(array("status" => "error", "data" => "Upload failed."));
    exit();
}

$pp_file_name = $foo->file_dst_name;
$pp_save_path = "dist/profile_pictures/" . $pp_file_name;

$stmt = $conn->prepare("UPDATE users SET pp=? WHERE id=?");
$userIdInt = (int) $result['id'];
$stmt->bind_param("si", $pp_save_path, $userIdInt);
$success = $stmt->execute();
$stmt->close();

if ($success) {
    $foo->clean();
    //eski fotografi ancak yenisi gercekten kaydedildikten sonra sil
    if ($old_pp != null && $old_pp != $pp_save_path && strpos($old_pp, "dist/profile_pictures/") === 0) {
        @unlink("../../" . $old_pp);
    }
    echo json_encode(array("status" => "success", "data" => array("pp" => $pp_save_path)));
} else {
    echo json_encode(array("status" => "error", "data" => "Database update failed."));
}
$conn->close();
