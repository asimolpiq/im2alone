<?php
function getUserSearch($conn, $search_text)
{
    try {
        if($search_text != ''){
            $arr = array();
            $sorgu = "SELECT * FROM users WHERE username LIKE '%$search_text%'";
            $sonuc = mysqli_query($conn, $sorgu);
            $count = mysqli_num_rows($sonuc);
            if ($count != 0) {
                while ($satir = mysqli_fetch_array($sonuc)) {
                    $username = $satir['username'];
                    $realname= $satir['realname'];
                    $pp2 =  $satir['pp'];
                    if ($satir['pp'] == '') {
                      $pp2 = "empty";
                    }
                    $bio = $satir['bio'];
                    $id = $satir['id'];
                    array_push($arr, ["username" => $username, "realname" => $realname, "pp" => $pp2, "bio" => $bio, "id" => $id]);
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
