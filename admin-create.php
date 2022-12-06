<?php require('includes/db_connect.php');
ob_start();
session_start();
if(!isset($_SESSION["im2alone_user"])){
  
  header("Location:index.php");
}
else {
  $im2alone_user = $_SESSION["im2alone_user"];
  if($im2alone_user['permission']==0){
    header("Location:index.php");
  }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Admin Create</title>
<!-- Sabit Kütüphaneleri çektiğimiz yer -->
<?php require('includes/librarys.php'); ?>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5 shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<!-- sayfada veri tekrarını önleyen kod-->
<script>
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
    </script>

</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper boxed-wrapper">
  <header class="main-header"> 
    <!-- Logo --> 
   
      
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <?php
      require('includes/navbar.php'); 
    require('includes/left_menu.php'); ?>
    </div>
    <!-- /.sidebar --> 
  </aside>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <div class="content-header sty-one">
      <h1 class="text-white">Admin Create</h1>
      <ol class="breadcrumb">
        <li><a href="#">Home</a></li>
        <li class="sub-bread"><i class="fa fa-angle-right"></i> Pages</li>
        <li><i class="fa fa-angle-right"></i> Blank Page</li>
      </ol>
    </div>
    
    <!-- Main content -->
    <div class="content">
    <?php
    if (isset($_POST['admin_save']))
    {
      $username = trim($_POST['username']);
      $username = strip_tags($_POST['username']);
      $username = htmlspecialchars($_POST['username']);

      $recive_password = trim($_POST['pass']);
      $recive_password = strip_tags($_POST['pass']);
      $recive_password = htmlspecialchars($_POST['pass']);
      $password = md5($recive_password);
      $password = md5($password);

      $status = $_POST['status'];
      $error = false;

      $query = "SELECT username FROM users WHERE username='$username'";
        $result = mysqli_query($conn,$query);
        $count = mysqli_num_rows($result);
        if($count!=0){
          $error=true;
          echo "<div class='alert alert-danger' role='alert'> Username allready used dude :( </div>";
          header("Refresh:3; url=admin-create.php");
        }
        else{
          if (!preg_match('^[0-9A-Za-z_]+$^',$username)) {
            $error = true;
            echo "<div class='alert alert-danger' role='alert'> Username or Realname must contain alphabets and space dude :( </div>";
            //header("Refresh:3; url=admin-create.php");
           }
           else{
            if(!$error){
              $admin_save = "INSERT INTO users (username,password,permission,status) VALUES ('$username','$password',1,'$status')";

              if ($conn->query($admin_save)){
              echo "<div class='alert alert-success' role='alert'> Admin Create Succesful! </div>";
              header("Refresh:3; url=admin-create.php");
              }
              else{
                echo "<div class='alert alert-danger' role='alert'> Admin Create Failed! </div>";
              }
             }
           }
          }


     

    }
    ?>
    <form class="uk-form-stacked uk-margin-medium-top" method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data">
      <div class="col-lg-4">
        <fieldset class="form-group">
          <label>Username:</label>
          <input class="form-control" id="basicInput" type="text" name="username" required>
        </fieldset>
      </div>

      <div class="col-lg-4">
        <fieldset class="form-group">
          <label>Password:</label>
          <input class="form-control" id="basicInput" type="password" name="pass" required>
        </fieldset>
      </div>

      <div class="col-lg-4">
        <div class="form-group">
          <label for="location1">Permission Status :</label>
          <select class="custom-select form-control" id="location1" name="status" required>
            <option value="1">Active</option>
            <option value="0">Passive</option>
          </select>
        </div>
      </div>

      <div class="col-lg-4">
        <fieldset class="form-group"></fieldset>
        <button type="submit" class="btn btn-success" name="admin_save">Save</button>
      </fieldset>
      </div>

      </form>
      <br>
      <hr>
      <br>
        <!-- tablo başlangıç -->
          <?php
            if(isset($_GET['sil'])){
              $id = $_GET['sil'];
              $sql = "DELETE FROM users WHERE id=$id";
              if ($conn->query($sql,) === TRUE) {
                echo "<div class='alert alert-success' role='alert'> Admin Deleted. </div>";
                header("refresh:3; location:admin-create.php");
              } else {
                echo "Admin can not deleted: " . $conn->error;
              }
            }
          ?>
          <div class="table-responsive col-lg-6">
            <h4 >| Admin List |</h4>
            <br>
            <table class="table">
              <thead>
                <tr>
                <th scope="col">İd</th>
                <th scope="col">Username</th>
                <th scope="col">Password</th>
                <th scope="col">Permission Status</th>
                <th scope="col">Edit</th>
                </tr>
                </thead>
                <tbody>
                  <?php
                    $sonuc=mysqli_query($conn,"SELECT * from users WHERE permission='1'");
                    while($satir=mysqli_fetch_array($sonuc))
                    {
                      $status ="";
                      if($satir['permission']==1){
                        $status="Active";
                      }
                      else{
                        $status="Passive";
                      }
                       echo '<tr>';
                       echo "<th scope='row'>",$satir['id'],"</th>";
                       echo '<td>',$satir['username'],'</td>';
                       echo '<td>',$satir['password'],'</td>';
                       echo '<td>',$status,'</td>';
                       echo "<td><a href='?sil=" ,$satir['id'],"' class='btn btn-rounded btn-danger'>Delete</a></td>";
                    }

                  ?>

              </tbody>
            </table>
            </div>
    </div>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->
  <?php require('includes/footer.php'); ?>
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