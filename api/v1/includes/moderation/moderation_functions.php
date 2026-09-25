<?php
require_once __DIR__ . '/../../../../PHPMailer/SMTP.php';
require_once __DIR__ . '/../../../../PHPMailer/Exception.php';
require_once __DIR__ . '/../../../../PHPMailer/PHPMailer.php';

use PHPMailer\PHPMailer\PHPMailer;

$VALID_REPORT_REASONS = array('nudity', 'harassment', 'spam', 'violence', 'hate_speech', 'other');

function createReport($conn, $reporterID, $reportedUserID, $feedID, $reason, $description)
{
    global $VALID_REPORT_REASONS;
    try {
        if ($reporterID == $reportedUserID) {
            return false;
        }
        if (!in_array($reason, $VALID_REPORT_REASONS, true)) {
            $reason = 'other';
        }

        $stmt = $conn->prepare(
            "INSERT INTO reports (reporter_id, reported_user_id, feed_id, reason, description) VALUES (?, ?, ?, ?, ?)"
        );
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("iiiss", $reporterID, $reportedUserID, $feedID, $reason, $description);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            sendReportNotificationEmail($conn, $reporterID, $reportedUserID, $feedID, $reason, $description);
        }

        return $success;
    } catch (Exception $e) {
        return false;
    }
}

function sendReportNotificationEmail($conn, $reporterID, $reportedUserID, $feedID, $reason, $description)
{
    try {
        $reporter = getUserBasicInfo($conn, $reporterID);
        $reported = getUserBasicInfo($conn, $reportedUserID);

        $feedContent = '';
        if ($feedID) {
            $feedResult = $conn->query("SELECT content FROM feeds WHERE id=" . (int) $feedID);
            if ($feedResult && $feedResult->num_rows === 1) {
                $feedRow = $feedResult->fetch_assoc();
                $feedContent = strip_tags($feedRow['content']);
            }
        }

        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = "mail.im2alone.com";
        $mail->Port = 465;
        $mail->IsHTML(true);
        $mail->CharSet = "utf-8";
        $mail->Username = "info@im2alone.com";
        $mail->Password = "MAİL PASSWORD";
        $mail->SetFrom("info@im2alone.com", "im2alone Moderation");
        $mail->AddAddress("info@im2alone.com");
        $mail->Subject = "[im2alone] Yeni içerik/kullanıcı şikayeti (" . $reason . ")";
        $mail->Body =
            "<p><b>Şikayet eden:</b> " . htmlspecialchars($reporter['username'] ?? $reporterID) . " (ID: $reporterID)</p>" .
            "<p><b>Şikayet edilen:</b> " . htmlspecialchars($reported['username'] ?? $reportedUserID) . " (ID: $reportedUserID)</p>" .
            "<p><b>Sebep:</b> " . htmlspecialchars($reason) . "</p>" .
            "<p><b>Açıklama:</b> " . nl2br(htmlspecialchars($description ?? '')) . "</p>" .
            ($feedContent !== '' ? "<p><b>İlgili günlük içeriği:</b><br>" . nl2br(htmlspecialchars($feedContent)) . "</p>" : "") .
            "<p>Lütfen bu şikayeti Apple Guideline 1.2 gereği <b>24 saat içinde</b> im2alone.com/reports.php üzerinden değerlendirin.</p>";
        $mail->smtpConnect([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        $mail->Send();
    } catch (Exception $e) {
    }
}

function getUserBasicInfo($conn, $userID)
{
    $result = $conn->query("SELECT id, username, pp FROM users WHERE id=" . (int) $userID);
    if ($result && $result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    return null;
}
?>
