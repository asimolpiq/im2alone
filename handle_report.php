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

$adminID = (int) $im2alone_user['id'];
$reportID = isset($_GET['report_id']) ? (int) $_GET['report_id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

$reportResult = mysqli_query($conn, "SELECT * FROM reports WHERE id='$reportID' AND status='pending'");
$report = $reportResult ? mysqli_fetch_assoc($reportResult) : null;

if ($report) {
  $reportedUserID = (int) $report['reported_user_id'];
  $feedID = $report['feed_id'] ? (int) $report['feed_id'] : null;

  if ($action === 'ban') {
    if ($feedID) {
      mysqli_query($conn, "DELETE FROM feeds WHERE id='$feedID'");
      mysqli_query($conn, "DELETE FROM feed_likes WHERE feed_id='$feedID'");
      mysqli_query($conn, "DELETE FROM feed_views WHERE feed_id='$feedID'");
    }
    mysqli_query($conn, "UPDATE users SET is_banned=1, token='', online=0 WHERE id='$reportedUserID'");
    mysqli_query($conn, "UPDATE reports SET status='actioned', resolved_at=NOW(), resolved_by='$adminID' WHERE id='$reportID'");
  } elseif ($action === 'delete_content') {
    if ($feedID) {
      mysqli_query($conn, "DELETE FROM feeds WHERE id='$feedID'");
      mysqli_query($conn, "DELETE FROM feed_likes WHERE feed_id='$feedID'");
      mysqli_query($conn, "DELETE FROM feed_views WHERE feed_id='$feedID'");
    }
    mysqli_query($conn, "UPDATE reports SET status='actioned', resolved_at=NOW(), resolved_by='$adminID' WHERE id='$reportID'");
  } elseif ($action === 'dismiss') {
    mysqli_query($conn, "UPDATE reports SET status='dismissed', resolved_at=NOW(), resolved_by='$adminID' WHERE id='$reportID'");
  }

  if ($action === 'ban' && $feedID) {
    mysqli_query($conn, "UPDATE reports SET status='actioned', resolved_at=NOW(), resolved_by='$adminID' WHERE feed_id='$feedID' AND status='pending'");
  }
}

header("Location: reports.php");
exit();
