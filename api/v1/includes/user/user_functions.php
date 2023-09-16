<?php
function addFriend($conn, $myID, $friendID)
{
    try {
        $isFriendExists = "SELECT * FROM friends WHERE (userid1='$myID' AND userid2='$friendID') OR (userid1='$friendID' AND userid2='$myID')";
        $friendExistsResult = $conn->query($isFriendExists);
        if ($friendExistsResult->num_rows > 0) {
            return false;
        }
        $isExists = "SELECT * FROM friend_request WHERE (sender='$myID' AND receiver='$friendID') OR (sender='$friendID' AND receiver='$myID')";
        $existsResult = $conn->query($isExists);
        if ($existsResult->num_rows > 0) {
            return false;
        }

        $request_save = "INSERT INTO friend_request (sender,receiver) VALUES ('$myID','$friendID')";
        if ($conn->query($request_save)) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return array("error" => $e->getMessage());
    }
}

function getNotifications($conn,$myID){
    $sonuc=mysqli_query($conn,"SELECT * FROM friend_request WHERE receiver='$myID'");
    $count = $sonuc->num_rows;
    if($count==0){
        return array("error" => "You don't have a notification.");
    }
    else{
        $notifications = array();
        while($satir=mysqli_fetch_array($sonuc))
        {   
            $friendid = $satir['sender'];
            $get_username = mysqli_query($conn, "SELECT * FROM users WHERE id ='$friendid'"); //whats my friend username?
            while ($satir5 = mysqli_fetch_array($get_username)) {
                $pp1 = $satir5['pp'];
                if ($satir5['pp'] == "") {
                    $pp1 = "dist/img/img5.jpg";
                }
                $frnd_username = $satir5['username'];
                $notifications[] = array(
                    "pp" => $pp1,
                    "username" => $frnd_username,
                    "message" => "Sent you a friend request"
                );
            }
        }
        return $notifications;
    }
}

function getUserStats($conn,$user_id){
    if($user_id == "null"){
        return null;
    }
    try{
        $diary_count = mysqli_query($conn,"SELECT * FROM feeds WHERE user_id='$user_id'");
        $diary_count = $diary_count->num_rows;
       
        $follower_count = mysqli_query($conn,"SELECT * FROM friends WHERE userid2='$user_id'");
        $follower_count = $follower_count->num_rows;
        $following_count = mysqli_query($conn,"SELECT * FROM friends WHERE userid1='$user_id'");
        $following_count = $following_count->num_rows;
        $stats = array(
            "diary_count" => $diary_count,
            "follower_count" => $follower_count,
            "following_count" => $following_count
        );
        return $stats;
    }
    catch(Exception $e){
        return null;
    }
   
}
?>
