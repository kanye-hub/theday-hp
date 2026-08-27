<?php
// ▼ PHPMailer本体を読み込み（PHPMailer/src/ に3ファイルを配置しておくこと）
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: text/html; charset=UTF-8');

// POST以外のアクセスは弾く
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('不正なアクセスです。');
}

// フォームの値を取得
$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$email_confirm = trim($_POST['email_confirm'] ?? '');
$tel      = trim($_POST['tel'] ?? '');
$company  = trim($_POST['company'] ?? '');
$purpose = $_POST['purpose'] ?? []; 
$purpose_str = !empty($purpose) ? implode('、', $purpose) : '';
$message = trim($_POST['message'] ?? ''); 

// 必須チェック
if ($name === '' || $email === '' || empty($purpose) || $message === '') {
    exit('必須項目が入力されていません。ブラウザの「戻る」で入力画面に戻ってください。');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit('メールアドレスの形式が正しくありません。');
}
if ($email !== $email_confirm) {
    exit('メールアドレスが一致しません。ブラウザの「戻る」で入力画面に戻ってください。');
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
      // ▼▼▼ ここを自分のGmail情報に書き換えてください ▼▼▼
    $mail->Username   = 'Kanye7fs@gmail.com'; 
    $mail->Password   = 'qbpr qfvx jytn zpsd';           // Googleアカウントで発行した16桁のアプリパスワード
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom('Kanye7fs@gmail.com', 'THEday株式会社 お問い合わせフォーム');
    $mail->addAddress('Kanye7fs@gmail.com'); // 受信したいアドレス（自分宛でOK）
    $mail->addReplyTo($email, $name);            // 返信ボタンで問い合わせ者に直接返信できるように

    $mail->Subject = '【お問い合わせ】' . $name . '様より';
    $mail->Body =
        "用件：{$purpose_str}\n" .
        "氏名：{$name}\n" .
        "会社名：" . ($company !== '' ? $company : '（未入力）') . "\n" .
        "メールアドレス：{$email}\n" .
        "電話番号：" . ($tel !== '' ? $tel : '（未入力）') . "\n" .
        "お問い合わせ内容：\n{$message}";

    $mail->send();

    // 送信成功後、アラートを出してcontact.htmlに戻す
    echo '<script>alert("お問い合わせを送信しました。ご連絡ありがとうございました。");location.href="contact.html";</script>';

} catch (Exception $e) {
    echo '送信に失敗しました：' . htmlspecialchars($mail->ErrorInfo, ENT_QUOTES, 'UTF-8');
}
