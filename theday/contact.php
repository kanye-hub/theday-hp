<?php
// contact.php

$name = $_POST['name'] ?? '';
$kana = $_POST['kana'] ?? '';
$company = $_POST['company'] ?? '';
$email = $_POST['email'] ?? '';
$email_confirm = $_POST['email_confirm'] ?? '';
$tel = $_POST['tel'] ?? '';
$message = $_POST['message'] ?? '';
$purpose = $_POST['purpose'] ?? [];

$errors = [];

if ($name === '') { $errors[] = "name"; }
if ($kana === '') { $errors[] = "kana"; }
if ($email === '') { $errors[] = "email"; }
if ($email !== $email_confirm) {
    $errors[] = "email";
    $errors[] = "email_confirm";
}
if (mb_strlen($message) < 10) { $errors[] = "message"; }
if (count($purpose) === 0) { $errors[] = "purpose"; }

if (count($errors) > 0) {
    $error_list = implode(",", $errors);
    header("Location: contact.html?errors=" . $error_list);
    exit;
}

// ここまで来たら、エラーなし。受け取った内容を表示
echo "<h2>送信が完了しました</h2>";
echo "お問い合わせありがとうございました。<br><br>";
echo "お名前: " . htmlspecialchars($name) . "<br>";
echo "フリガナ: " . htmlspecialchars($kana) . "<br>";
echo "会社名: " . htmlspecialchars($company) . "<br>";
echo "メールアドレス: " . htmlspecialchars($email) . "<br>";
echo "電話番号: " . htmlspecialchars($tel) . "<br>";
echo "用件: " . htmlspecialchars(implode("、", $purpose)) . "<br>";
echo "お問い合わせ内容: " . nl2br(htmlspecialchars($message)) . "<br>";
?>