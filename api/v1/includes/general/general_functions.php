<?php
function getUserSearch($conn, $search_text, $my_id)
{
    try {
        if($search_text != ''){
            $arr = array();
            $sorgu = "SELECT * FROM users WHERE username LIKE '%$search_text%'";
            $sonuc = mysqli_query($conn, $sorgu);
            $count = mysqli_num_rows($sonuc);
            if ($count != 0) {
                while ($satir = mysqli_fetch_array($sonuc)) {
                    $id = $satir['id'];
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
