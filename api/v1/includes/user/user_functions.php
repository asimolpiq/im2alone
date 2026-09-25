<?php
require_once(__DIR__ . '/../../../../includes/age_functions.php'); //13 yas kontrolu (web ile ortak)

function isBlocked($conn, $userID1, $userID2)
{
    $userID1 = (int) $userID1;
    $userID2 = (int) $userID2;
    $query = "SELECT id FROM blockeduser WHERE (userid1='$userID1' AND userid2='$userID2') OR (userid1='$userID2' AND userid2='$userID1')";
    $result = $conn->query($query);
    return $result && $result->num_rows > 0;
}

function blockUser($conn, $myID, $targetID)
{
    try {
        $myID = (int) $myID;
        $targetID = (int) $targetID;
        if ($myID == $targetID) {
            return false;
        }
        if (isBlocked($conn, $myID, $targetID)) {
            return true;
        }
        $conn->query("DELETE FROM friends WHERE (userid1='$myID' AND userid2='$targetID') OR (userid1='$targetID' AND userid2='$myID')");
        $conn->query("DELETE FROM friend_request WHERE (sender='$myID' AND receiver='$targetID') OR (sender='$targetID' AND receiver='$myID')");
        $insert = "INSERT INTO blockeduser (userid1,userid2) VALUES ('$myID','$targetID')";
        return (bool) $conn->query($insert);
    } catch (Exception $e) {
        return false;
    }
}

function unblockUser($conn, $myID, $targetID)
{
    try {
        $myID = (int) $myID;
        $targetID = (int) $targetID;
        $delete = "DELETE FROM blockeduser WHERE userid1='$myID' AND userid2='$targetID'";
        return (bool) $conn->query($delete);
    } catch (Exception $e) {
        return false;
    }
}

function getBlockedUsers($conn, $myID)
{
    $myID = (int) $myID;
    $blocked = array();
    $result = $conn->query("SELECT userid2 FROM blockeduser WHERE userid1='$myID'");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $userResult = $conn->query("SELECT id, username, pp FROM users WHERE id='" . (int) $row['userid2'] . "'");
            if ($userResult && $userResult->num_rows === 1) {
                $user = $userResult->fetch_assoc();
                $blocked[] = array(
                    "id" => $user['id'],
                    "username" => $user['username'],
                    "pp" => $user['pp'] == "" ? null : $user['pp'],
                );
            }
        }
    }
    return $blocked;
}

function getFollowers($conn, $myID)
{
    //takipci: bana istek gonderip benim kabul ettigim kisiler (userid1=beni kabul eden, userid2=orijinal gonderen)
    $myID = (int) $myID;
    $followers = array();
    $result = $conn->query("SELECT userid2 FROM friends WHERE userid1='$myID'");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $userResult = $conn->query("SELECT id, username, pp, bio FROM users WHERE id='" . (int) $row['userid2'] . "'");
            if ($userResult && $userResult->num_rows === 1) {
                $user = $userResult->fetch_assoc();
                $followers[] = array(
                    "id" => $user['id'],
                    "username" => $user['username'],
                    "pp" => $user['pp'] == "" ? null : $user['pp'],
                    "bio" => $user['bio'],
                );
            }
        }
    }
    return $followers;
}

function getFollowing($conn, $myID)
{
    //takip: benim istek gonderip karsi tarafin kabul ettigi kisiler (userid2=beni kabul eden karsi taraf degil, ben; userid1=beni kabul eden)
    $myID = (int) $myID;
    $following = array();
    $result = $conn->query("SELECT userid1 FROM friends WHERE userid2='$myID'");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $userResult = $conn->query("SELECT id, username, pp, bio FROM users WHERE id='" . (int) $row['userid1'] . "'");
            if ($userResult && $userResult->num_rows === 1) {
                $user = $userResult->fetch_assoc();
                $following[] = array(
                    "id" => $user['id'],
                    "username" => $user['username'],
                    "pp" => $user['pp'] == "" ? null : $user['pp'],
                    "bio" => $user['bio'],
                );
            }
        }
    }
    return $following;
}

function addFriend($conn, $myID, $friendID)
{
    try {
        $myID = (int) $myID;
        $friendID = (int) $friendID;
        if (isBlocked($conn, $myID, $friendID)) {
            return false;
        }
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

function deleteFriend($conn, $myID, $friendID)
{
    try {
        $myID = (int) $myID;
        $friendID = (int) $friendID;
        $isFriendExists = "SELECT * FROM friends WHERE (userid1='$myID' AND userid2='$friendID') OR (userid1='$friendID' AND userid2='$myID')";
        $friendExistsResult = $conn->query($isFriendExists);
        if ($friendExistsResult->num_rows === 0) {
            return false;
        }
        $delete = "DELETE FROM friends WHERE (userid1='$myID' AND userid2='$friendID') OR (userid1='$friendID' AND userid2='$myID')";
        if ($conn->query($delete)) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return array("error" => $e->getMessage());
    }
}

function getNotifications($conn,$myID){
    $myID = (int) $myID;
    $sonuc=mysqli_query($conn,"SELECT * FROM friend_request WHERE receiver='$myID'");
    $count = $sonuc->num_rows;
    if($count==0){
        return null;
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
                    $pp1 = null;
                }
                $id = $satir5['id'];
                $frnd_username = $satir5['username'];
                $bio = $satir5['bio'];
                $notifications[] = array(
                    "id" => $satir['id'],
                    "pp" => $pp1,
                    "username" => $frnd_username,
                    "friend_id" => $id,
                    "bio" => $bio
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
    $user_id = (int) $user_id;
    try{
        $diary_count = mysqli_query($conn,"SELECT * FROM feeds WHERE user_id='$user_id'");
        $diary_count = $diary_count->num_rows;
       
        $follower_count = mysqli_query($conn,"SELECT * FROM friends WHERE userid2='$user_id'");
        $follower_count = $follower_count->num_rows;
        $following_count = mysqli_query($conn,"SELECT * FROM friends WHERE userid1='$user_id'");
        $following_count = $following_count->num_rows;
        $stats = array(
            "diary_count" => $diary_count,
            "follower_count" => $following_count,
            "following_count" => $follower_count
        );
        return $stats;
    }
    catch(Exception $e){
        return null;
    }
   
}

function editProfile($conn, $username, $fullname, $email, $gender, $birthday, $bio, $userID)
{
    try {
        $userIDInt = (int) $userID;

        //aynı kurallar web'deki profile-page.php'de
        if (!preg_match('/^[0-9A-Za-z_]+$/', $username) || !preg_match('/^[\p{L}0-9_ ]+$/u', $fullname)) {
            return array("success" => false, "message" => "invalid_characters");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array("success" => false, "message" => "invalid_email");
        }
        //dogum tarihi gecerli olmali, 13 yas altina cekilemez
        $birthday = normalizeBirthday($birthday);
        if ($birthday === null) {
            return array("success" => false, "message" => "invalid_birthday");
        }
        if (isUnderMinAge($birthday)) {
            return array("success" => false, "message" => "underage");
        }

        $username_stmt = $conn->prepare("SELECT id FROM users WHERE username=? AND id<>?");
        $username_stmt->bind_param("si", $username, $userIDInt);
        $username_stmt->execute();
        $usernameTaken = $username_stmt->get_result()->num_rows > 0;
        $username_stmt->close();
        if ($usernameTaken) {
            return array("success" => false, "message" => "username_taken");
        }

        $email_stmt = $conn->prepare("SELECT id FROM users WHERE email=? AND id<>?");
        $email_stmt->bind_param("si", $email, $userIDInt);
        $email_stmt->execute();
        $emailTaken = $email_stmt->get_result()->num_rows > 0;
        $email_stmt->close();
        if ($emailTaken) {
            return array("success" => false, "message" => "email_taken");
        }

        $genderInt = (int) $gender;
        $stmt = $conn->prepare("UPDATE users SET username=?, realname=?, email=?, gender=?, birthday=?, bio=? WHERE id=?");
        if (!$stmt) {
            return array("success" => false, "message" => "server_error");
        }
        $stmt->bind_param("sssissi", $username, $fullname, $email, $genderInt, $birthday, $bio, $userIDInt);
        $success = $stmt->execute();
        $stmt->close();
        if ($success) {
            return array("success" => true);
        }
        return array("success" => false, "message" => "server_error");
    } catch (Exception $e) {
        return array("success" => false, "message" => "server_error");
    }
}

function changePassword($conn,$new_password,$userID){
    try{
        $new_password = md5(md5($new_password));
        $userID = (int) $userID;
        $update = "UPDATE users SET password='$new_password' WHERE id='$userID'";
        if($conn->query($update)){
            return true;
        }
        else{
            return false;
        }
    }
    catch(Exception $e){
        return false;
    }
}

function acceptFriend($conn,$friend_id,$my_id){
    try{
        $friend_id = (int) $friend_id;
        $my_id = (int) $my_id;
        $sonuc=mysqli_query($conn,"SELECT id FROM friend_request WHERE receiver='$my_id' AND sender= '$friend_id'");
        while($satir=mysqli_fetch_array($sonuc))
        {
        $request_id=$satir['id'];
        }

        $delete_request = "DELETE FROM friend_request WHERE id='$request_id'";
        if ($conn->query($delete_request)){
            $friend_save = "INSERT INTO friends (userid1,userid2) VALUES ('$my_id','$friend_id')";
            if ($conn->query($friend_save)){
                return true;
            }
        }
    }
    catch(Exception $e){
        return false;
    }
}

function ignoreUser($conn,$friend_id,$my_id){
    try{
        $friend_id = (int) $friend_id;
        $my_id = (int) $my_id;
        $sonuc=mysqli_query($conn,"SELECT id FROM friend_request WHERE receiver='$my_id' AND sender= '$friend_id'");
        while($satir=mysqli_fetch_array($sonuc))
        {
        $request_id=$satir['id'];
        }

        $delete_request = "DELETE FROM friend_request WHERE id='$request_id'";
        if ($conn->query($delete_request)){
            return true;
        }
    }
    catch(Exception $e){
        return false;
    }
}

?>
