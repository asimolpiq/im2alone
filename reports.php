<?php require('includes/db_connect.php');
ob_start();
session_start();
if (!isset($_SESSION["im2alone_user"])) {
  header("Location:index.php");
  exit();
}
$im2alone_user = $_SESSION["im2alone_user"];
if ($im2alone_user['permission'] == 0) {
  header("Location:index.php");
  exit();
}

$reports = array();
$result = mysqli_query($conn, "SELECT * FROM reports WHERE status='pending' ORDER BY created_at ASC");
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $reporter = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM users WHERE id='" . (int) $row['reporter_id'] . "'"));
    $reported = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username, is_banned FROM users WHERE id='" . (int) $row['reported_user_id'] . "'"));
    $feedContent = null;
    if ($row['feed_id']) {
      $feedRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT content FROM feeds WHERE id='" . (int) $row['feed_id'] . "'"));
      if ($feedRow) {
        $feedContent = $feedRow['content'];
      }
    }
    $row['reporter_username'] = $reporter['username'] ?? ('#' . $row['reporter_id']);
    $row['reported_username'] = $reported['username'] ?? ('#' . $row['reported_user_id']);
    $row['reported_is_banned'] = $reported['is_banned'] ?? 0;
    $row['feed_content'] = $feedContent;
    $reports[] = $row;
  }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Reports</title>
  <?php require('includes/librarys_app.php'); ?>
  <script>
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
</head>

<body class="skin-blue sidebar-mini">
  <div class="wrapper boxed-wrapper">
    <header class="main-header">
      <?php
      require('includes/navbar.php');
      require('includes/left_menu.php'); ?>
    </div>
    </aside>

    <div class="content-wrapper">
      <div class="content-header sty-one">
        <h1 class="text-white text-center">Reports — 24 saat içinde değerlendirilmelidir</h1>
      </div>

      <div class="content">
        <div class="info-box">
          <?php if (empty($reports)): ?>
            <p class="text-center">Bekleyen şikayet yok.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table">
                <thead class="bg-danger">
                  <tr>
                    <th>#</th>
                    <th>Tarih</th>
                    <th>Şikayet Eden</th>
                    <th>Şikayet Edilen</th>
                    <th>Sebep</th>
                    <th>Açıklama</th>
                    <th>İçerik</th>
                    <th>İşlem</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($reports as $report): ?>
                    <tr>
                      <td><?= htmlspecialchars($report['id']) ?></td>
                      <td><?= htmlspecialchars($report['created_at']) ?></td>
                      <td><?= htmlspecialchars($report['reporter_username']) ?></td>
                      <td>
                        <?= htmlspecialchars($report['reported_username']) ?>
                        <?php if ($report['reported_is_banned'] == 1): ?>
                          <span class="label label-danger">Banlı</span>
                        <?php endif; ?>
                      </td>
                      <td><?= htmlspecialchars($report['reason']) ?></td>
                      <td><?= nl2br(htmlspecialchars($report['description'])) ?></td>
                      <td>
                        <?php if ($report['feed_content'] !== null): ?>
                          <?= htmlspecialchars(mb_substr(strip_tags($report['feed_content']), 0, 200)) ?>
                        <?php else: ?>
                          <em>-</em>
                        <?php endif; ?>
                      </td>
                      <td>
                        <a class="btn btn-xs btn-danger" href="handle_report.php?report_id=<?= (int) $report['id'] ?>&action=ban" onclick="return confirm('İçeriği sil ve kullanıcıyı banla?');">İçeriği Sil + Banla</a><br><br>
                        <?php if ($report['feed_content'] !== null): ?>
                          <a class="btn btn-xs btn-warning" href="handle_report.php?report_id=<?= (int) $report['id'] ?>&action=delete_content" onclick="return confirm('Sadece içeriği sil?');">Sadece İçeriği Sil</a><br><br>
                        <?php endif; ?>
                        <a class="btn btn-xs btn-default" href="handle_report.php?report_id=<?= (int) $report['id'] ?>&action=dismiss" onclick="return confirm('Şikayeti işlem yapmadan kapat?');">Reddet</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script src="dist/js/jquery.min.js"></script>
  <script src="dist/bootstrap/js/bootstrap.min.js"></script>
  <script src="dist/js/niche.js"></script>
</body>

</html>
