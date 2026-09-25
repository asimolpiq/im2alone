<?php
use PHPMailer\PHPMailer\PHPMailer;

//sends the account confirmation mail. returns true/false instead of dying,
//so register can keep going and ask for the confirmation later if mail is down
function sendConfirmationMail($email, $username, $token)
{
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 0; // hata ayiklama: 1 = hata ve mesaj, 2 = sadece mesaj
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl'; // Güvenli baglanti icin ssl normal baglanti icin tls
        $mail->Host = "mail.im2alone.com"; // Mail sunucusuna ismi
        $mail->Port = 465; // Gucenli baglanti icin 465 Normal baglanti icin 587
        $mail->Timeout = 10; //mail sunucusu cevap vermezse sayfayi asirtma
        $mail->IsHTML(true);
        $mail->SetLanguage("en", "phpmailer/language");
        $mail->CharSet = "utf-8";
        $mail->Username = "info@im2alone.com"; // Mail adresimizin kullanicı adi
        $mail->Password = "MAİL PASSWORD"; // Mail adresimizin sifresi
        $mail->SetFrom("info@im2alone.com", "Confirm System"); // Mail attigimizda gorulecek ismimiz
        $mail->AddAddress($email); // Maili gonderecegimiz kisi yani alici
        $mail->Subject = "Please confirm your account."; // Konu basligi
        $mail->Body = "Please click the link and confirm your account. <a href='https://im2alone.com/confirm.php?username=" . urlencode($username) . "&token=$token'>Click Me!</a>"; // Mailin icerigi
        $mail->smtpConnect(array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        ));
        return (bool) $mail->Send();
    } catch (Exception $e) {
        return false;
    }
}
