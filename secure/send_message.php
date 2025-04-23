<?php
// Database connection
$host = 'localhost'; // Replace with your database host
$username = 'root';  // Replace with your database username
$password = '';      // Replace with your database password
$dbname = 'esther'; // Replace with your database name

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Database connection failed']));
}

// Retrieve POST data
$message = $_POST['message'] ?? '';
$isEncrypted = $_POST['is_encrypted'] === '1' ? 1 : 0;

if (empty($message)) {
    echo json_encode(['success' => false, 'error' => 'Message is required']);
    exit;
}

// Insert message into the database
$stmt = $conn->prepare("INSERT INTO messagess (message, is_encrypted) VALUES (?, ?)");
$stmt->bind_param("si", $message, $isEncrypted);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to save message']);
}
$stmt->close();
$conn->close();
?>
