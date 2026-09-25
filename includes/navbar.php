<?php
require_once('includes/csrf.php');
$im2alone_user = $_SESSION["im2alone_user"];
$my_id = (int) $im2alone_user['id'];
$pp = $im2alone_user['pp'];
if($pp=="") {$pp="dist/img/img1.jpg";}

//notification state: the bell only animates when there is something unread
$unread_notif_q = mysqli_query($conn, "SELECT COUNT(*) FROM friend_request WHERE receiver='$my_id' AND is_read=0");
$unread_notif_row = mysqli_fetch_row($unread_notif_q);
$unread_notif_count = $unread_notif_row ? (int) $unread_notif_row[0] : 0;

//email confirm state: session can be stale (user may confirm mid-session), check fresh
$verify_q = mysqli_query($conn, "SELECT status FROM users WHERE id='$my_id'");
$verify_row = mysqli_fetch_row($verify_q);
$email_unverified = ($verify_row && (int) $verify_row[0] == 0);
?>
<a href="index.php" class="logo blue-bg"> 
    <!-- mini logo for sidebar mini 50x50 pixels --> 
    <span class="logo-mini"><img src="dist/img/logo-n.png" alt=""></span> 
    <!-- logo for regular state and mobile devices --> 
    <span class="logo-lg"><img src="dist/img/logo.png" alt=""></span> </a> 
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar blue-bg navbar-static-top"> 
      <!-- Sidebar toggle button-->
      <ul class="nav navbar-nav pull-left">
        <li><a class="sidebar-toggle" data-toggle="push-menu" href=""></a> </li>
      </ul>
      <div class="pull-left search-box">
        <form action="search.php" method="POST"  class="search-form" enctype="multipart/form-data">
          <div class="input-group">
            <input name="search_text" class="form-control text-black" placeholder="Search a Username..." type="text" >
            <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i> </button>
            </span></div>
        </form>
        <!-- search form --> </div>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
         
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown messages-menu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-bell-o"></i>
            <?php if ($unread_notif_count > 0) { echo '<div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>'; } ?>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Notifications<?php if ($unread_notif_count > 0) { echo '<a href="#" id="notif-mark-read" class="notif-mark-read">Mark all as read</a>'; } ?></li>
              <li>
                <ul class="menu">
                <?php
                    $my_username = $im2alone_user['username'];
                    $sonuc=mysqli_query($conn,"SELECT * FROM friend_request WHERE receiver='$my_id'");
                    $count = $sonuc->num_rows;
                    if($count==0){
                     echo "<li>
                      <h4 class='text-black'>$my_username</h4>
                      <p class='text-black'>You don't have a notification.</p>
                      </li>";
                    }
                    else{
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

                          echo  "<li><a href='user_detail.php?username=$frnd_username'>
                          
                          <img class='pull-left img-circle img-responsive' src='$pp1' width='40px' height='40px' alt='User Image' >
                          <h4>$frnd_username</h4>
                          <p>Sent you a friend request</p>
                          </a></li>";

                        }
                      }
                    }
                    
                  ?>
                </ul>
              </li>
              <li class="footer"><a href="social_settings.php">Check all Notifications</a></li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu p-ph-res"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img src="<?=$pp?>" class="user-image" alt="User Image"> <span class="hidden-xs"><?php echo $im2alone_user['username']?></span> </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <div class="pull-left user-img"><img src="<?=$pp?>" class="img-responsive" alt="User"></div>
                <p class="text-left"><?php echo $im2alone_user['username']?> <small><?php echo $im2alone_user['realname']?></small> </p>
                <div class="view-link text-left"><a href="user_detail.php?username=<?=$im2alone_user['username']?>">View Profile</a> </div>
              </li>
              <li><a href="user_detail.php?username=<?=$im2alone_user['username']?>"><i class="icon-profile-male"></i> My Profile</a></li>
              <li><a href="social_settings.php"><i class="fa fa-users"></i> Social Settings</a></li>
              <li><a href="profile-page.php"><i class="icon-gears"></i> Account Setting</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
    <input type="hidden" id="app-csrf-token" value="<?= csrfToken() ?>">
    <?php if ($email_unverified) { ?>
    <div class="verify-banner" id="verify-banner">
      <i class="fa fa-envelope-o"></i>
      <span id="verify-banner-text">Your email address is not confirmed yet.</span>
      <a href="#" id="verify-resend" class="btn btn-primary btn-rounded btn-sm">Resend confirmation email</a>
    </div>
    <?php } ?>
    <script>
    //resend the confirmation mail from the banner
    document.addEventListener('DOMContentLoaded', function () {
      var resend = document.getElementById('verify-resend');
      if (!resend) { return; }
      resend.addEventListener('click', function (e) {
        e.preventDefault();
        resend.classList.add('disabled');
        var fd = new FormData();
        fd.append('csrf_token', '<?= csrfToken() ?>');
        fetch('resend_verification.php', { method: 'POST', body: fd })
          .then(function (r) { return r.json(); })
          .then(function (res) {
            document.getElementById('verify-banner-text').textContent = res.message;
            if (res.success) { resend.remove(); }
            else { resend.classList.remove('disabled'); }
          })
          .catch(function () { resend.classList.remove('disabled'); });
      });
    });
    </script>
    <script>
    //mark all notifications as read without a page reload
    document.addEventListener('DOMContentLoaded', function () {
      var markRead = document.getElementById('notif-mark-read');
      if (!markRead) { return; }
      markRead.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation(); //keep the dropdown open
        var fd = new FormData();
        fd.append('csrf_token', '<?= csrfToken() ?>');
        fetch('mark_notifications_read.php', { method: 'POST', body: fd })
          .then(function (r) { return r.json(); })
          .then(function (res) {
            if (res.success) {
              var notify = document.querySelector('.messages-menu .notify');
              if (notify) { notify.remove(); }
              markRead.remove();
            }
          });
      });
    });
    </script>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar"> 
    <!-- sidebar: style can be found in sidebar.less -->
    <div class="sidebar"> 
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="image text-center"><img src="<?=$pp?>"  class="img-circle" alt="User Image"> </div>
        <div class="info">
          <p><?php echo $im2alone_user['username']?></p>
          <a href="profile-page.php"><i class="fa fa-cog"></i></a><a href="logout.php"><i class="fa fa-power-off"></i></a> </div>
      </div>
      