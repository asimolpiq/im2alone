<?php
function getUserSearch($conn, $search_text, $my_id)
{
    try {
        if($search_text != ''){
            $arr = array();
            $my_id = (int) $my_id;
            $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE ?");
            $likeParam = '%' . $search_text . '%';
            $stmt->bind_param("s", $likeParam);
            $stmt->execute();
            $sonuc = $stmt->get_result();
            $count = $sonuc->num_rows;
            if ($count != 0) {
                while ($satir = mysqli_fetch_array($sonuc)) {
                    $id = $satir['id'];
                    if ($id == $my_id) {
                        continue;
                    }
                    $isBlockedQuery = mysqli_query($conn, "SELECT id FROM blockeduser WHERE (userid1='$id' AND userid2='$my_id') OR (userid1='$my_id' AND userid2='$id')");
                    if (mysqli_num_rows($isBlockedQuery) > 0) {
                        continue;
                    }
                    $isFriendQuery = mysqli_query($conn, "SELECT * FROM friends WHERE (userid1='$id' AND userid2='$my_id') OR (userid1='$my_id' AND userid2='$id')");
                    $isFriendCount = mysqli_num_rows($isFriendQuery);
                    if ($isFriendCount == 0) {
                       $isRequestedFriend = mysqli_query($conn, "SELECT * FROM friend_request WHERE (sender='$my_id' AND receiver='$id') OR (sender='$id' AND receiver='$my_id')");
                          $isRequestedFriendCount = mysqli_num_rows($isRequestedFriend);
                            if ($isRequestedFriendCount == 0) {
                                $isFriend = false;
                            } else {
                                $isFriend = null;
                            }
                    } else {
                        $isFriend = true;
                    }
                    $username = $satir['username'];
                    $realname= $satir['realname'];
                    $pp2 =  $satir['pp'];
                    if ($satir['pp'] == '') {
                      $pp2 = null;
                    }
                    $bio = $satir['bio'];
                   
                    array_push($arr, ["username" => $username, "realname" => $realname, "pp" => $pp2, "bio" => $bio, "id" => $id, "isFriend" => $isFriend]);
                }
            } else {
               null;
            }
            return $arr;
        }
       
    } catch (Exception $e) {
        return array("error" => $e->getMessage());
    }
}
?>
