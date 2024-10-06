<?php
header('Content-Type: application/json');

// Параметры подключения к базе данных
$servername = "localhost:3306";
$username = "root";
$password = "root";
$dbname = "student_voting";

// Создаем подключение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем подключение
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Ошибка соединения с базой данных."]));
}

// Получаем данные из формы
$studentId = $_POST['studentId'] ?? '';
$name = $_POST['name'] ?? '';
$class = $_POST['class'] ?? '';
$classCode = $_POST['classCode'] ?? '';

// Проверка на пустые значения
if (empty($studentId) || empty($name) || empty($class) || empty($classCode)) {
    echo json_encode(["success" => false, "message" => "Все поля обязательны для заполнения."]);
    $conn->close();
    exit();
}

// Проверяем, существует ли студент с таким номером студенческого билета
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("s", $studentId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Студент с таким номером уже существует."]);
} else {
    // Вставляем нового студента в таблицу
    $stmt = $conn->prepare("INSERT INTO students (student_id, name, class, class_code) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $studentId, $name, $class, $classCode);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Ошибка: " . $stmt->error]);
    }
}

// Закрываем соединение с базой данных
$stmt->close();
$conn->close();
?>
