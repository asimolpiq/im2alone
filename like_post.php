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
$feed_id = isset($_POST['feed_id']) ? (int) $_POST['feed_id'] : 0;
if ($feed_id <= 0) {
    echo json_encode(array("success" => false, "message" => "Invalid post."));
    exit();
}

$feed_stmt = $conn->prepare("SELECT user_id, privacy FROM feeds WHERE id=?");
$feed_stmt->bind_param("i", $feed_id);
$feed_stmt->execute();
$feed_result = $feed_stmt->get_result();
$feed = $feed_result->fetch_assoc();
$feed_stmt->close();
if (!$feed) {
    echo json_encode(array("success" => false, "message" => "Post not found."));
    exit();
}

$owner_id = (int) $feed['user_id'];
$privacy = (int) $feed['privacy'];

//can this user even see the post? same rules as post_detail
$can_see = false;
if ($owner_id == $my_id) {
    $can_see = true;
} elseif ($privacy == 2 || $privacy == 1) {
    $blocked_stmt = $conn->prepare("SELECT id FROM blockeduser WHERE (userid1=? AND userid2=?) OR (userid1=? AND userid2=?)");
    $blocked_stmt->bind_param("iiii", $my_id, $owner_id, $owner_id, $my_id);
    $blocked_stmt->execute();
    $is_blocked = $blocked_stmt->get_result()->num_rows > 0;
    $blocked_stmt->close();
    if (!$is_blocked) {
        if ($privacy == 2) {
            $can_see = true;
        } else {
            $friend_stmt = $conn->prepare("SELECT id FROM friends WHERE (userid1=? AND userid2=?) OR (userid1=? AND userid2=?)");
            $friend_stmt->bind_param("iiii", $my_id, $owner_id, $owner_id, $my_id);
            $friend_stmt->execute();
            $can_see = $friend_stmt->get_result()->num_rows > 0;
            $friend_stmt->close();
        }
    }
}
if (!$can_see) {
    echo json_encode(array("success" => false, "message" => "You can not like this post."));
    exit();
}

//toggle: liked before? unlike. otherwise like
$like_stmt = $conn->prepare("SELECT id FROM feed_likes WHERE feed_id=? AND user_id=?");
$like_stmt->bind_param("ii", $feed_id, $my_id);
$like_stmt->execute();
$existing = $like_stmt->get_result()->fetch_assoc();
$like_stmt->close();

if ($existing) {
    $del_stmt = $conn->prepare("DELETE FROM feed_likes WHERE id=?");
    $del_id = (int) $existing['id'];
    $del_stmt->bind_param("i", $del_id);
    $del_stmt->execute();
    $del_stmt->close();
    $liked = false;
} else {
    $like_date = date("Y/m/d");
    $ins_stmt = $conn->prepare("INSERT IGNORE INTO feed_likes (feed_id,user_id,date) VALUES (?,?,?)");
    $ins_stmt->bind_param("iis", $feed_id, $my_id, $like_date);
    $ins_stmt->execute();
    $ins_stmt->close();
    $liked = true;
}

$count = 0;
$count_q = mysqli_query($conn, "SELECT COUNT(id) FROM feed_likes WHERE feed_id='$feed_id'");
if ($count_q && ($row = mysqli_fetch_row($count_q))) {
    $count = (int) $row[0];
}

echo json_encode(array("success" => true, "liked" => $liked, "count" => $count));
?>
