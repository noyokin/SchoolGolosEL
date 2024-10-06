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

// Получаем данные из формы
$studentId = $_POST['studentId'] ?? null;
$classCode = $_POST['classCode'] ?? null;
$vote = $_POST['vote'] ?? null;

if (!$studentId || !$classCode || !$vote) {
    echo json_encode(['success' => false, 'message' => 'Не все данные формы заполнены!']);
    exit;
}

// Проверка, голосовал ли студент ранее
$sql = "SELECT * FROM votes WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studentId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Если студент уже голосовал
    echo json_encode(['success' => false, 'message' => 'Вы уже голосовали!']);
    exit;
}

$stmt->close();

// Проверка правильности кода класса
$sql = "SELECT class_code FROM students WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $studentId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Проверяем правильность кода класса
    if ($row['class_code'] === $classCode) {
        // Код класса верный, записываем голос
        $voteSql = "INSERT INTO votes (student_id, vote) VALUES (?, ?)";
        $voteStmt = $conn->prepare($voteSql);
        $voteStmt->bind_param("ss", $studentId, $vote);

        if ($voteStmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ошибка записи голоса!']);
        }
        $voteStmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Неверный код класса!']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Студент не найден!']);
}

$stmt->close();
$conn->close();
?>