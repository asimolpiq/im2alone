<?php
function getMyDiaries($conn, $user_id)
{
  $response = mysqli_query($conn, "SELECT * FROM feeds WHERE user_id ='$user_id' ORDER BY id DESC");
  $count = mysqli_num_rows($response);
  if ($count > 0) {
    $diaries = [];
    while ($satir = mysqli_fetch_array($response)) {
      $date = $satir['date'];
      $id = $satir['id'];
      $user_id = $satir['user_id'];
      $content = $satir['content'];
      $link = "";
      if ($satir['link'] != "") {
        $link = $satir['link'];
        $link = str_replace("track/", "embed/track/", $link);
      }

      $current_diary = array("id" => $id, "content" => $content, "date" => $date, "link" => $link,"user_id"=>"$user_id");
      array_push($diaries, $current_diary);
    }
    return $diaries;
  } else {
    return null;
  }
}

function getAllFeed($conn, $user_id)
{
  $date = null;
  $friendid = null;
  $friend_name = null;
  $arr = [];

  $query = "SELECT * FROM friends WHERE userid1='$user_id' OR userid2='$user_id' ORDER BY id DESC";
  $result = mysqli_query($conn, $query);
  $count = mysqli_num_rows($result);
  if ($count != 0) { // ı have a friend. 
    while ($friend_rows = mysqli_fetch_array($result)) {
      $userid1 = $friend_rows['userid1'];
      $userid2 = $friend_rows['userid2'];

      if ($userid1 == $user_id) { //whic my friend id?
        $friendid = $userid2;
      } else {
        $friendid = $userid1;
      }

      $friend_posts = mysqli_query($conn, "SELECT * FROM feeds WHERE (privacy='1' OR privacy='2') AND user_id ='$friendid' ORDER BY id DESC");
      if ($friend_posts == null) { //my friend have not posts
        return ["error" => "Your Friend Have Not a Diary"];
      } else {
        while ($satir = mysqli_fetch_array($friend_posts)) { //my friend have posts
          $date = $satir['date'];
          $friend_name = mysqli_query($conn, "SELECT * FROM users WHERE id ='$friendid'");
          $friend_name = mysqli_fetch_array($friend_name);
          $friend_name = $friend_name['username'];
          $content = $satir['content'];
          $user_id = $satir['user_id'];
          $link = "";
          if ($satir['link'] != "") {
            $link = $satir['link'];
            $link = str_replace("track/", "embed/track/", $link);
          }

          $current_diary = array("content" => $content, "date" => $date, "link" => $link, "friend_name" => $friend_name, "user_id" => $user_id);
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
  $feels_query = "INSERT INTO feeds (user_id,content,link,date,privacy) VALUES ('$user_id','$content','$link','$date','$privacy')";
  try {
    mysqli_query($conn, $feels_query);
    return true;
  } catch (Exception $e) {
    return false;
  }
}

function deleteDiary($conn, $diary_id)
{
  $sql = "DELETE FROM feeds WHERE id=$diary_id";
  if ($conn->query($sql,) === TRUE) {
    return true;
  } else {
    return false;
  }
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
