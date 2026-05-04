<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['api'])) {
    ini_set('display_errors', 0);
    error_reporting(0);
    header('Content-Type: application/json; charset=utf-8');

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    $a = $data['a'];
    $b = $data['b'];
    $op = $data['op'];

    if (!is_numeric($a) || !is_numeric($b)) {
        echo json_encode(['error' => 'Введите числа']);
        exit;
    }

    switch ($op) {
        case '+': $result = $a + $b; break;
        case '-': $result = $a - $b; break;
        case '*': $result = $a * $b; break;
        case '/':
            if ($b == 0) {
                echo json_encode(['error' => 'Делить на ноль нельзя']);
                exit;
            }
            $result = $a / $b;
            break;
        default:
            echo json_encode(['error' => 'Ошибка']);
            exit;
    }

    echo json_encode(['result' => $result]);
    exit;
}

if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Калькулятор</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Калькулятор</h1>
    <form action="logout.php" method="post">
        <button>Выйти</button>
    </form> 

<div class="calculator">
    <input type="text" id="display" readonly>

    <div class ="buttons">
    <button onclick="press('7')">7</button>
    <button onclick="press('8')">8</button>
    <button onclick="press('9')">9</button>
    <button onclick="pressOp('/')">/</button>

    <button onclick="press('4')">4</button>
    <button onclick="press('5')">5</button>
    <button onclick="press('6')">6</button>
    <button onclick="pressOp('*')">*</button>

    <button onclick="press('1')">1</button>
    <button onclick="press('2')">2</button>
    <button onclick="press('3')">3</button>
    <button onclick="pressOp('-')">-</button>

    <button onclick="press('0')">0</button>
    <button onclick="clearDisplay()">C</button>
    <button onclick="pressOp('+')">+</button>
    <button onclick="backspace()">⌫</button>
    
    <button onclick="calculate()" style="grid-column: 1 / -1; padding: 20px;">=</button>
    </div>
</div>

    <script src="script.js"></script>
</body>
</html>
