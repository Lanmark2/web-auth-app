<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

require "db.php";

$action = trim($_POST["action"] ?? '');
$email = trim($_POST["email"] ?? '');
$username = trim($_POST["username"] ?? '');
$password = $_POST["password"] ?? '';

if ($action === "register") {
    $code = rand(100000, 999999);
    if (empty($email) || empty($username) || empty($password)) {
        echo "Заполните все поля<br><br>
        <form action='index.html'>
            <button>Вернуться</button>
        </form>";
        exit();
    }
    $stmt = $conn->prepare("Select id from users where email=? OR username=?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Пользователь с такой почтой или логином уже существует<br><br>
        <form action='index.html'>
            <button>Вернуться</button>
        </form>";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("Insert into users (username, email, password, email_code, is_verified) values (?, ?, ?, ?, 0)");
        $stmt->bind_param("ssss", $username, $email, $hash, $code);
        if ($stmt->execute()) {
             require_once "mail.php";
             if (sendVerifyMail($email, $code)) {
                header("Location: verify.html?email=". urlencode($email));
                exit();
             } else {
                echo "Ошибка отправки письма.";
             }
             
        } else {
            echo "Ошибка регистрации: " . $stmt->error;
        }
      }
    }
if ($action === "login") {
    if (empty($email) || empty($password)) {
        echo "Заполните все поля<br><br>
        <form action='login.html'>
            <button>Вернуться</button>
        </form>";
        exit();
    }

    $stmt = $conn->prepare("Select password, username, is_verified from users where email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {

        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password'])) {
            if ($row['is_verified'] == 0) {
                echo "Ваша почта не подтверждена.<br><br>
                <form action='verify.html'>
                  <input type='hidden' name='email' value='$email'>
                  <button>Подтвердить почту</button>
                </form>
                ";
                exit();
            }
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $email;
            header("Location: calculator.php");
            exit();
        } else {
            echo "
            Неверный пароль<br><br>
            <form action='login.html'>
                 <button>Вернуться</button>
            </form>
                 ";
        }
    } else {
        echo "
        Пользователь не найден<br><br>
        <form action='login.html'>
             <button>Вернуться</button>
        </form>
        ";
        
    }
}