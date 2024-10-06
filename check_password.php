<?php
header('Content-Type: application/json');

// Подключение к базе данных
$servername = "localhost:3306";
$username = "root";
$password = "root";
$dbname = "student_voting";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Ошибка соединения с базой данных."]));
}

// Получаем пароль из запроса
$entered_password = $_POST['password'] ?? '';

// Извлекаем хеш пароля из базы данных
$sql = "SELECT password_hash FROM admin_password ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stored_password_hash = $row['password_hash'];

    // Проверяем введённый пароль с хешем
    if (password_verify($entered_password, $stored_password_hash)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Неправильный пароль']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Пароль не найден']);
}

$conn->close();
?>
