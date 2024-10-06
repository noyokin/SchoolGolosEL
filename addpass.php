<?php
// Подключение к базе данных
$servername = "localhost:3306";
$username = "root";
$password = "root";
$dbname = "student_voting";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Замените 'admin123' на ваш реальный пароль
$plain_password = 'admin123';
$password_hash = password_hash($plain_password, PASSWORD_DEFAULT);

// Добавление хешированного пароля в базу данных
$sql = "INSERT INTO admin_password (password_hash) VALUES ('$password_hash')";

if ($conn->query($sql) === TRUE) {
    echo "Пароль успешно добавлен в базу данных.";
} else {
    echo "Ошибка: " . $conn->error;
}

$conn->close();
?>
