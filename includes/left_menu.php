<?php
@ob_start();
@session_start();
$status = "";

if(!isset($_SESSION["im2alone_user"])){
    header("Location:index.php");
}
else {
  $im2alone_user = $_SESSION["im2alone_user"];
  if($im2alone_user['status']==0){
    header("Location:logout.php");
  }
  if($im2alone_user['permission']==0){$status = "hidden";}
}
?>
<ul class="sidebar-menu" data-widget="tree">
        <li class="header">WE ARE STRONGER TOGETHER</li>

        <li class="active"> <a href="dashboard.php"> <i class="ti  ti-comment-alt"></i> <span>&nbsp; Feeds</span>  </a></li>
        <li class="active"> <a href="rooms.php"> <i class="ti  ti-comments"></i> <span>&nbsp; Chat Rooms</span>  </a></li>
        <li class="active"> <a href="write-diary.php"> <i class="ti  ti-pencil"></i> <span>&nbsp; Write a Diary</span> </a></li>
        <li class="active"> <a href="my-diary.php"> <i class="ti  ti-bookmark-alt"></i> <span>&nbsp; My Diary</span>  </a></li>
        <li class="active"> <a href="social_settings.php"> <i class="ti  ti-settings"></i> <span>&nbsp; Social Settings</span>  </a></li>
        <li class="active"> <a href="profile-page.php"> <i class="ti  ti-user"></i> <span>&nbsp; Account Settings</span>  </a></li>

        <li class="active treeview" <?=$status?>> <a href="#"> <i class="fa fa-dashboard"></i> <span>Admin Settings</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
          <ul class="treeview-menu">
            <li><a href="admin-create.php">Admin Create</a></li>
            <li><a href="create_room.php">Room Create</a></li>
            <li><a href="social_settings.php">Social Setting</a></li>
          </ul>
        </li>
      
      
      </ul>
  