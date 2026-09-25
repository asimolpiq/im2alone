<?php
require_once(__DIR__ . '/../../../../includes/feed_stats.php');

function getMyDiaries($conn, $user_id)
{
  $user_id = (int) $user_id;
  $response = mysqli_query($conn, "SELECT * FROM feeds WHERE user_id ='$user_id' ORDER BY id DESC");
  $count = mysqli_num_rows($response);
  if ($count > 0) {
    $diaries = [];
    while ($satir = mysqli_fetch_array($response)) {
      $date = $satir['date'];
      $id = $satir['id'];
      $user_id = $satir['user_id'];
      $content = strip_tags($satir['content']); //app renders plain text, dont ship raw html
      $link = "";
      if ($satir['link'] != "") {
        $link = $satir['link'];
        $link = str_replace("track/", "embed/track/", $link);
      }
      $stats = getFeedStats($conn, $id, $user_id);

      $current_diary = array("id" => $id, "content" => $content, "date" => $date, "link" => $link, "user_id" => "$user_id", "likes" => $stats['likes'], "views" => $stats['views'], "liked" => $stats['liked']);
      array_push($diaries, $current_diary);
    }
    return $diaries;
  } else {
    return null;
  }
}

function getAllFeed($conn, $my_id)
{
  $my_id = (int) $my_id;
  $date = null;
  $friendid = null;
  $friend_name = null;
  $arr = [];

  $query = "SELECT * FROM friends WHERE userid1='$my_id' OR userid2='$my_id' ORDER BY id DESC";
  $result = mysqli_query($conn, $query);
  $count = mysqli_num_rows($result);
  if ($count != 0) { // ı have a friend. 
    while ($friend_rows = mysqli_fetch_array($result)) {
      $userid1 = $friend_rows['userid1'];
      $userid2 = $friend_rows['userid2'];

      if ($userid1 == $my_id) { //whic my friend id?
        $friendid = $userid2;
      } else {
        $friendid = $userid1;
      }

      $isBlockedQuery = mysqli_query($conn, "SELECT id FROM blockeduser WHERE (userid1='$friendid' AND userid2='$my_id') OR (userid1='$my_id' AND userid2='$friendid')");
      if (mysqli_num_rows($isBlockedQuery) > 0) {
        continue;
      }

      $friend_posts = mysqli_query($conn, "SELECT * FROM feeds WHERE (privacy='1' OR privacy='2') AND user_id ='$friendid' ORDER BY id DESC");
      if ($friend_posts == null) { //my friend have not posts
        return ["error" => "Your Friend Have Not a Diary"];
      } else {
        while ($satir = mysqli_fetch_array($friend_posts)) { //my friend have posts
          $date = $satir['date'];
          $friend_name = mysqli_query($conn, "SELECT * FROM users WHERE id ='$friendid'");
          $friend_name = mysqli_fetch_array($friend_name);
          if ($friend_name['pp'] == "") {
            $pp = null;
          }
          else{
            $pp = utf8ize($friend_name['pp']) ;
          }
          $friend_name = utf8ize($friend_name['username']) ;

          $feed_id = $satir['id'];
          $content = strip_tags($satir['content']); //app renders plain text, dont ship raw html
          $user_id = $satir['user_id'];
          $link = "";
          if ($satir['link'] != "") {
            $link = $satir['link'];
            $link = str_replace("track/", "embed/track/", $link);
          }
          recordFeedView($conn, $feed_id, $my_id, $user_id);
          $stats = getFeedStats($conn, $feed_id, $my_id);

          $current_diary = array("id" => $feed_id, "content" => $content, "date" => $date, "link" => $link, "friend_name" => $friend_name, "user_id" => $user_id, "pp" => $pp, "likes" => $stats['likes'], "views" => $stats['views'], "liked" => $stats['liked']);
          array_push($arr, $current_diary);
        }
      }
    }

    if (empty($arr)) { //my friend post is empty?
      return ["error" => "Your Friend Have Not a Diary"];
    } else {
      usort($arr, sort_date('date')); //sort array
      return $arr;
    }
  } else { //ı dont have a friend :(
    return ["error" => "You Have Not a Friend"];
  }
}

function writeDiary($conn, $user_id, $content, $link, $privacy)
{
  $date = date('l jS \of F Y h:i:s A');
  $content = "<p>" . $content . "</p>";
  $stmt = $conn->prepare("INSERT INTO feeds (user_id,content,link,date,privacy) VALUES (?,?,?,?,?)");
  if (!$stmt) {
    return false;
  }
  $userIdInt = (int) $user_id;
  $privacyInt = (int) $privacy;
  $stmt->bind_param("isssi", $userIdInt, $content, $link, $date, $privacyInt);
  try {
    $success = $stmt->execute();
    $stmt->close();
    return $success;
  } catch (Exception $e) {
    return false;
  }
}

function deleteDiary($conn, $diary_id, $user_id)
{
  $stmt = $conn->prepare("DELETE FROM feeds WHERE id = ? AND user_id = ?");
  if (!$stmt) {
    return false;
  }
  $diary_id = (int) $diary_id;
  $stmt->bind_param("ii", $diary_id, $user_id);
  $success = $stmt->execute();
  $affected = $stmt->affected_rows;
  $stmt->close();
  if ($success && $affected > 0) { //begeni ve goruntulenme kayitlari da gitsin
    mysqli_query($conn, "DELETE FROM feed_likes WHERE feed_id='$diary_id'");
    mysqli_query($conn, "DELETE FROM feed_views WHERE feed_id='$diary_id'");
  }
  return $success && $affected > 0;
}

function toggleFeedLike($conn, $feed_id, $my_id)
{
  $feed_id = (int) $feed_id;
  $my_id = (int) $my_id;

  $feed_stmt = $conn->prepare("SELECT user_id, privacy FROM feeds WHERE id=?");
  $feed_stmt->bind_param("i", $feed_id);
  $feed_stmt->execute();
  $feed = $feed_stmt->get_result()->fetch_assoc();
  $feed_stmt->close();
  if (!$feed) {
    return array("success" => false, "message" => "Post not found.");
  }

  $owner_id = (int) $feed['user_id'];
  $privacy = (int) $feed['privacy'];

  //same visibility rules as the web like button
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
    return array("success" => false, "message" => "You can not like this post.");
  }

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

  $count_stmt = $conn->prepare("SELECT COUNT(id) FROM feed_likes WHERE feed_id=?");
  $count_stmt->bind_param("i", $feed_id);
  $count_stmt->execute();
  $count_row = $count_stmt->get_result()->fetch_row();
  $count_stmt->close();
  $count = $count_row ? (int) $count_row[0] : 0;

  return array("success" => true, "liked" => $liked, "count" => $count);
}

function sort_date($key) //array date sort function
{
  return function ($lt, $rt) use ($key) {
    if ($lt[$key] < $rt[$key]) {
      return 1;
    } elseif ($lt[$key] > $rt[$key]) {
      return -1;
    } else {
      return 0;
    }
  };
}
