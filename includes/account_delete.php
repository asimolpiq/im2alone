<?php
//deletes a user and everything they own. used by the web (delete_account.php)
//and the mobile api (api/v1/delete-account.php)
function deleteUserAccount($conn, $user_id)
{
    $user_id = (int) $user_id;

    $user_q = mysqli_query($conn, "SELECT username, pp FROM users WHERE id='$user_id'");
    $user_row = $user_q ? mysqli_fetch_assoc($user_q) : null;
    if (!$user_row) {
        return false;
    }
    $username = $user_row['username'];
    $pp_path = $user_row['pp'];
    $esc_username = mysqli_real_escape_string($conn, $username);
    $root = dirname(__DIR__); //site koku (includes'in bir ustu), api'den cagrilsa da dogru

    //diary photos of this user (upload endpoint names them diary{id}_*)
    foreach (glob($root . "/dist/diary_images/diary" . $user_id . "_*") as $img_file) {
        @unlink($img_file);
    }
    //profile picture file
    if ($pp_path != "" && strpos($pp_path, "dist/profile_pictures/") === 0 && file_exists($root . "/" . $pp_path)) {
        @unlink($root . "/" . $pp_path);
    }

    //likes/views on this users posts, then the posts themselves
    $feed_q = mysqli_query($conn, "SELECT id FROM feeds WHERE user_id='$user_id'");
    if ($feed_q) {
        while ($f = mysqli_fetch_row($feed_q)) {
            $fid = (int) $f[0];
            mysqli_query($conn, "DELETE FROM feed_likes WHERE feed_id='$fid'");
            mysqli_query($conn, "DELETE FROM feed_views WHERE feed_id='$fid'");
        }
    }
    mysqli_query($conn, "DELETE FROM feeds WHERE user_id='$user_id'");
    //likes/views this user left on other posts
    mysqli_query($conn, "DELETE FROM feed_likes WHERE user_id='$user_id'");
    mysqli_query($conn, "DELETE FROM feed_views WHERE user_id='$user_id'");

    mysqli_query($conn, "DELETE FROM friends WHERE userid1='$user_id' OR userid2='$user_id'");
    mysqli_query($conn, "DELETE FROM friend_request WHERE sender='$user_id' OR receiver='$user_id'");
    mysqli_query($conn, "DELETE FROM blockeduser WHERE userid1='$user_id' OR userid2='$user_id'");
    mysqli_query($conn, "DELETE FROM log WHERE userid='$user_id'");
    mysqli_query($conn, "DELETE FROM reports WHERE reporter_id='$user_id' OR reported_user_id='$user_id'");
    mysqli_query($conn, "DELETE FROM tokens WHERE username='$esc_username'");
    mysqli_query($conn, "DELETE FROM chat_online WHERE username='$esc_username'");

    //chat messages in every *_room table
    $tables_q = mysqli_query($conn, "SHOW TABLES");
    if ($tables_q) {
        while ($t = mysqli_fetch_row($tables_q)) {
            if (strpos($t[0], "_room") !== false) {
                mysqli_query($conn, "DELETE FROM `" . $t[0] . "` WHERE username='$esc_username'");
            }
        }
    }

    //finally the user itself
    $del_stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $del_stmt->bind_param("i", $user_id);
    $ok = $del_stmt->execute();
    $affected = $del_stmt->affected_rows;
    $del_stmt->close();
    return $ok && $affected > 0;
}
