<?php require('includes/db_connect.php');
ob_start();
session_start();
if (!isset($_SESSION["im2alone_user"])) {
    header("Location:index.php");
} else {
    $im2alone_user = $_SESSION["im2alone_user"];
}

$user_info;
if (empty($_GET["username"])) {
    header("Location:index.php");
} else {
    $search_text = $_GET["username"];
    $search_text = $conn->real_escape_string($search_text);
    $sorgu = "SELECT * FROM users WHERE username LIKE '%$search_text%'";
    $sonuc = mysqli_query($conn, $sorgu);
    $count = mysqli_num_rows($sonuc);
    if ($count != 0) {
        while ($satir = mysqli_fetch_array($sonuc)) {
            $user_info = $satir;

        }
    } else {
        echo "User not found!";
        header("Refresh:2; url=index.php");
    }
}

$my_id =  $im2alone_user['id'];
$friend_id = $user_info['id'];
$allready_blocked =  mysqli_query($conn, "SELECT * FROM blockeduser WHERE (userid1='$my_id' AND userid2='$friend_id') OR (userid1='$friend_id' AND userid2='$my_id')");
$count_blocked = mysqli_num_rows($allready_blocked);
if ($count_blocked != 0) {
    header("Location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $_GET["username"] ?></title>
    <?php
    require("includes/librarys.php");
    
    ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

</head>

<body class="skin-blue sidebar-mini">
    <div class="wrapper boxed-wrapper">
        <header class="main-header">
            <?php
            require("includes/navbar.php");
            $birthday = $user_info["birthday"];
            $birthday = substr($birthday,0,5);
            $now_date = date('d/m');
            if($now_date==$birthday){
                echo "<div class='alert alert-warning text-center' role='alert'>
                Happy Birthday $search_text! ve hope be good year for u <i><svg xmlns='http://www.w3.org/2000/svg'  width='16' height='16' fill='currentColor' class='bi bi-balloon-heart' viewBox='0 0 16 16'>
                    <path fill-rule='evenodd' d='m8 2.42-.717-.737c-1.13-1.161-3.243-.777-4.01.72-.35.685-.451 1.707.236 3.062C4.16 6.753 5.52 8.32 8 10.042c2.479-1.723 3.839-3.29 4.491-4.577.687-1.355.587-2.377.236-3.061-.767-1.498-2.88-1.882-4.01-.721L8 2.42Zm-.49 8.5c-10.78-7.44-3-13.155.359-10.063.045.041.089.084.132.129.043-.045.087-.088.132-.129 3.36-3.092 11.137 2.624.357 10.063l.235.468a.25.25 0 1 1-.448.224l-.008-.017c.008.11.02.202.037.29.054.27.161.488.419 1.003.288.578.235 1.15.076 1.629-.157.469-.422.867-.588 1.115l-.004.007a.25.25 0 1 1-.416-.278c.168-.252.4-.6.533-1.003.133-.396.163-.824-.049-1.246l-.013-.028c-.24-.48-.38-.758-.448-1.102a3.177 3.177 0 0 1-.052-.45l-.04.08a.25.25 0 1 1-.447-.224l.235-.468ZM6.013 2.06c-.649-.18-1.483.083-1.85.798-.131.258-.245.689-.08 1.335.063.244.414.198.487-.043.21-.697.627-1.447 1.359-1.692.217-.073.304-.337.084-.398Z'/>
                  </svg></i>
                </div>";
            }
            require("includes/left_menu.php");
            
            ?>
    </div>
    <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <div class="content">
            <div class="row">
                <?php
                $info_text;
                $my_id =  $im2alone_user['id'];
                $friend_id = $user_info['id'];
                $allready_friend = mysqli_query($conn, "SELECT * FROM friends WHERE (userid1='$my_id' AND userid2='$friend_id') OR (userid1='$friend_id' AND userid2='$my_id')");
                $count_friend = mysqli_num_rows($allready_friend);
                if ($count_friend == 0) {

                    $send_me = mysqli_query($conn, "SELECT * FROM friend_request WHERE sender='$friend_id' AND receiver='$my_id'");
                    $count1 = mysqli_num_rows($send_me);
                    if ($count1 != 0) {
                        $info_text = "Accept";
                    } else {
                        $allready_sent = mysqli_query($conn, "SELECT * FROM friend_request WHERE sender='$my_id' AND receiver='$friend_id'");
                        $count = mysqli_num_rows($allready_sent);
                        if ($count == 0) {
                            $info_text = "Add Friend";
                        } else { //allready sent
                            $info_text = "Awaiting Response";
                        }
                    }
                } else {
                    $info_text = "You Are Friends";
                }
                ?>
                <div class="col-lg-12">

                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user ">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-primary">
                            <h3 class="widget-user-username text-center"><strong><?= $user_info['username'] ?></strong></h3>
                            <h5 class="widget-user-desc text-center"><strong><?= $user_info['realname'] ?></strong></h5>
                        </div>
                        <?php
                        $pp = $user_info['pp'];
                        if ($user_info['pp'] == "") {
                            $pp = "dist/img/img5.jpg";
                        }
                        ?>
                        <div class="widget-user-image"> <img class="img-circle" src="<?= $pp ?>" alt="User Avatar"> </div>
                        <div class="box-footer">
                            <div class="text-center">
                                <p> <?= $user_info['bio'] ?></p>
                                <?php
                                $my_id =  $im2alone_user['id'];
                                $friend_id = $user_info['id'];
                                if ($my_id != $friend_id) {
                                    if ($info_text == "Accept") {
                                        echo  "<a href='", "accept_friend.php?my_id=", $my_id, "&friend_id=", $friend_id, "' class='btn btn-facebook btn-rounded margin-bottom' name='add_friend'>$info_text</a>";
                                        echo  "<a href='", "refuse_friend.php?my_id=", $my_id, "&friend_id=", $friend_id, "' class='btn btn-facebook btn-rounded margin-bottom' name='add_friend'>Refuse</a>";
                                    } elseif ($info_text == "Add Friend") {
                                        echo  "<a href='", "add_friend.php?my_id=", $my_id, "&friend_id=", $friend_id, "' class='btn btn-facebook btn-rounded margin-bottom' name='add_friend'>$info_text</a>";
                                    } elseif ($info_text == "Awaiting Response") {
                                        echo  "<a href='#' class='btn btn-facebook btn-rounded margin-bottom' disabled>$info_text</a>";
                                    } elseif ($info_text == "You Are Friends") {
                                        echo  "<a href='", "delete_friend.php?my_id=", $my_id, "&friend_id=", $friend_id, "' class='btn btn-facebook btn-rounded margin-bottom' name='add_friend'>Delete Friend</a>";
                                        echo  "<a href='", "block_friend.php?my_id=", $my_id, "&friend_id=", $friend_id, "' class='btn btn-facebook btn-rounded margin-bottom' name='add_friend'>Block Friend</a>";
                                    }
                                }
                                $post_count;
                                $follower_count;
                                $following_count;
                                $reciever_id=$user_info['id'];
                                $posts_query = mysqli_query($conn,"SELECT COUNT(id) AS number FROM feeds WHERE user_id='$reciever_id'");
                                while ($satir1 = mysqli_fetch_array($posts_query)) {
                                  $post_count= $satir1['number'];
                                }
                                $follower_query = mysqli_query($conn,"SELECT COUNT(id) AS number FROM friends WHERE userid2='$reciever_id'");
                                while ($satir2 = mysqli_fetch_array($follower_query)) {
                                  $follower_count= $satir2['number'];
                                }
                                $following_query = mysqli_query($conn,"SELECT COUNT(id) AS number FROM friends WHERE userid1='$reciever_id'");
                                while ($satir3 = mysqli_fetch_array($following_query)) {
                                  $following_count= $satir3['number'];
                                }
                                ?>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header"><?= $post_count ?></h5>
                                        <span class="description-text">POST</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header"><?= $follower_count; ?></h5>
                                        <span class="description-text">FOLLOWERS</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header"><?= $following_count ?></h5>
                                        <span class="description-text">FOLLOWİNG</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                    <!-- /.widget-user -->
                </div>
            </div>

            <?php
            $search_text1 = $_GET["username"];
            $sorgu1 = sprintf("SELECT * FROM users WHERE username LIKE '%s'", $conn->real_escape_string($search_text1));
            $sonuc1 = mysqli_query($conn, $sorgu1);
            $count = mysqli_num_rows($sonuc1);
            if ($count != 0) {
                while ($satir = mysqli_fetch_array($sonuc1)) {
                    $user_info = $satir;
                    $pp = $user_info['pp'];
                    if ($user_info['pp'] == "") {
                        $pp = "dist/img/img1.jpg";
                    }
                }
            } else {
                echo "User not found!";
                header("Refresh:2; url=index.php");
            }
            $user_info_id = $user_info['id'];
            $user_info_username = $user_info['username'];
            $user_posts = mysqli_query($conn, "SELECT * FROM feeds WHERE privacy='2' AND user_id ='$user_info_id'  ORDER BY id DESC");
            $userpostisempty = mysqli_num_rows($user_posts);
            if ($userpostisempty != 0) { //user post is empty?
                echo "<div class='info-box'>";
                while ($post_rows = mysqli_fetch_array($user_posts)) { //my friend have posts
                    $date = $post_rows['date'];

                    echo "<div class='box box-widget'>";
                    echo    "<div class='box-header with-border'>";
                    echo      "<div class='user-block'> <img class='img-circle' src='$pp' alt='User Image'> <span class='username'><a href='user_detail.php?username=", $user_info_username, "'>", $user_info_username, "</a></span> <span class='description'>", $date, "</span> </div>";
                    echo    "</div>";
                    echo    "<div class='box-body pad'><br>", $post_rows['content'];
                    if ($post_rows['link'] != "") {
                        $link = $post_rows['link'];
                        $link = str_replace("track/", "embed/track/", $link);

                        echo "<iframe style='border-radius:12px' src='",
                        $link,
                        "?utm_source=generator' height='80' frameBorder='0' allowfullscreen='' allow='autoplay; clipboard-write; encrypted-media; fullscreen; '></iframe>";
                    }
                    echo     "</div>";
                    echo    "</div>";
                }
                echo    "</div>";
            }
            ?>
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php require("includes/footer.php"); ?>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery 3 -->
    <script src="dist/js/jquery.min.js"></script>

    <!-- v4.0.0-alpha.6 -->
    <script src="dist/bootstrap/js/bootstrap.min.js"></script>

    <!-- template -->
    <script src="dist/js/niche.js"></script>
</body>

</html>