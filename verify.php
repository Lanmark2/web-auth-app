<?php
require "db.php";

$email = trim($_POST["email"] ?? '');
$code = trim($_POST["code"] ?? '');

if (empty($email) || empty($code)) {
    echo "Заполните все поля.";
    exit();
}

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND email_code = ? AND is_verified = 0");
$stmt->bind_param("ss", $email, $code);
if (!$stmt->execute()) {
    echo "Ошибка базы данных: " . $stmt->error;
    exit();
}
$result = $stmt->get_result();

if ($result->num_rows === 1) {

    $stmt = $conn->prepare("UPDATE users SET is_verified = 1, email_code = NULL WHERE email = ?");
    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        echo "Ошибка при обновлении: " . $stmt->error;
        exit();
    }

    echo "
    <h2>Почта успешно подтверждена!<h2>
    <form action='login.html'>
        <button>Войти</button>
        </form>
        ";
} else {
    echo "
    <h2>Неверный код подтверждения или почта уже подтверждена.<h2>
    <form action='verify.html'>
        <button>Попробовать снова</button>
        </form>
        ";
}