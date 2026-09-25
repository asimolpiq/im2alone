<?php
require('includes/db_connect.php');
require('includes/html_sanitizer.php');
require('includes/csrf.php');
ob_start();
session_start();
if(!isset($_SESSION["im2alone_user"])){
  header("Location:index.php");
  exit();
}
else {
  $im2alone_user = $_SESSION["im2alone_user"];
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Write Your Feelings</title>
<!-- Tell the browser to be responsive to screen width -->
<link rel="stylesheet" href="dist/plugins/quill/quill.snow.css">
<?php require('includes/librarys_app.php'); ?>
<script src="dist/plugins/quill/quill.js"></script>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
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
    <?php
      require('includes/navbar.php'); 
    require('includes/left_menu.php'); ?>
    <!-- /.sidebar --> 
  </aside>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    
    
    <!-- Main content -->
    <div class="content">
    <?php
    if (isset($_POST['feels_save']))
    {
      $csrf_ok = isset($_POST['csrf_token']) && csrfTokenValid($_POST['csrf_token']);
      $content = sanitizeDiaryHtml(isset($_POST['feels']) ? $_POST['feels'] : ''); //clean the html before save, dont trust the editor
      $link = isset($_POST['link']) ? trim($_POST['link']) : "";
      $is_spotify_link = (bool) preg_match('#^https://open\.spotify\.com/[A-Za-z0-9/_.\-]+(\?[A-Za-z0-9=&_.\-%]*)?$#', $link);
      $privacy = (int) $_POST['privacy'];
      $user_id = (int) $im2alone_user['id'];
      $date = date('l jS \of F Y h:i:s A');
      if(!$csrf_ok){
        echo "<div class='alert alert-danger' role='alert'> Session expired, please refresh the page and try again. </div>";
      }
      elseif($content==""){
        echo "<div class='alert alert-danger' role='alert'> Please fill in the text field. </div>";
      }
      elseif(!$is_spotify_link){
        echo "<div class='alert alert-danger' role='alert'> Please paste a spotify link. </div>";
      }
      else{
        $feels_query = $conn->prepare("INSERT INTO feeds (user_id,content,link,date,privacy) VALUES (?,?,?,?,?)");
        try{
          $feels_query->bind_param("isssi", $user_id, $content, $link, $date, $privacy);
          $feels_query->execute();
          $feels_query->close();
          echo "<div class='alert alert-success' role='alert'> Save Successful! </div>";
          header("Refresh:3; url=my-diary.php");
        }
        catch(Exception $e){
          echo "<div class='alert alert-danger' role='alert'> Save Failed! Please review your text.</div>";
        }

      }
      
     
  }
    ?>
    <div class="col-lg-12">
          <div class="card card-outline">
            <div class="card-header bg-light">
              <h5 class="text-white m-b-0">Write Your Feelings</h5>
            </div>
            <div class="card-body">
            <form id="diary-form" class="uk-form-stacked uk-margin-medium-top" method="POST" action="" accept-charset="UTF-8" enctype="multipart/form-data">

            <div id="diary-editor"></div>
            <input type="hidden" name="feels" id="feels-input">
            <input type="hidden" name="csrf_token" id="csrf-token" value="<?= csrfToken() ?>">
              <br>
              <div class="form-group">
                <h5>Link:</h5>
                <input type="text" class="form-control" id="basicInput" name="link" placeholder="Leave a spotify link to remember these feelings dude.">
              </div>
              <div class="form-group">
              <h5>Privacy:</h5>
          <select class="custom-select form-control" id="location1" name="privacy" required>
            <option value="0">Only me</option>
            <option value="1">Only friends</option>
            <option value="2">Everyone</option>
          </select>
        </div>
              <button type="submit" name="feels_save" class="btn btn-success">save your thoughts</button>
            </form>
            </div>
          </div>
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

<script src="dist/plugins/popper/popper.min.js"></script> 
<script src="dist/bootstrap/js/bootstrap.beta.min.js"></script> 

<!-- template -->
<script src="dist/js/niche.js"></script>

<script>
//quill editor, only the formats we allow. pasted html gets stripped to these too
var quill = new Quill('#diary-editor', {
  theme: 'snow',
  placeholder: 'Write your feelings dude...',
  formats: ['bold', 'italic', 'underline', 'list', 'image'],
  modules: {
    toolbar: {
      container: [
        ['bold', 'italic', 'underline'],
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        ['image'],
        ['clean']
      ],
      handlers: { image: diaryImageUpload }
    }
  }
});

//upload the image to our own server, no external urls
function diaryImageUpload() {
  var input = document.createElement('input');
  input.type = 'file';
  input.accept = 'image/*';
  input.onchange = function () {
    var file = input.files[0];
    if (!file) { return; }
    var fd = new FormData();
    fd.append('diary_image', file);
    fd.append('csrf_token', document.getElementById('csrf-token').value);
    fetch('upload_diary_image.php', { method: 'POST', body: fd })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (res.success) {
          var range = quill.getSelection(true);
          quill.insertEmbed(range.index, 'image', res.url);
          quill.setSelection(range.index + 1);
        } else {
          alert(res.message || 'Upload failed.');
        }
      })
      .catch(function () { alert('Upload failed.'); });
  };
  input.click();
}

//put the editor html into the hidden input before submit
document.getElementById('diary-form').addEventListener('submit', function () {
  var html = quill.root.innerHTML;
  if (quill.getText().trim() === '' && quill.root.querySelectorAll('img').length === 0) {
    html = '';
  }
  document.getElementById('feels-input').value = html;
});
</script>
</body>
</html>