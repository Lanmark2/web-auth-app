<?php

$host = "onlyme.mysql.tools";
$user = "onlyme_calculator";
$password = "ZR4_6i5v_k";
$dbname = "onlyme_calculator";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}