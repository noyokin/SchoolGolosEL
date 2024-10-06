<?php
header('Content-Type: application/json');

// Параметры подключения к базе данных
$servername = "localhost:3306";
$username = "root";
$password = "root";
$dbname = "student_voting";

// Создаем подключение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получаем номер студенческого билета из POST-запроса
$studentId = $_POST['studentId'];

// Выполняем запрос для получения данных студента
$sql = "SELECT name, class FROM students WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studentId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['success' => true, 'name' => $row['name'], 'class' => $row['class']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Студент не найден!']);
}

$stmt->close();
$conn->close();
?>
