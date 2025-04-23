<?php
session_start();

// Fetch messages for a specific contact
if (isset($_GET['contact'])) {
    $contactName = $_GET['contact'];
    $userId = $_SESSION['user_id'];  // Get the logged-in user's ID

    // Fetch messages between the logged-in user and the contact
    $stmt = $conn->prepare("
        SELECT m.message, m.is_encrypted, m.sender_id
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        WHERE (m.sender_id = ? AND u.fullname = ?) OR (m.receiver_id = ? AND u.fullname = ?)
    ");
    $stmt->bind_param("isss", $userId, $contactName, $userId, $contactName);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch messages and return them as JSON
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = [
            'message' => $row['message'],
            'encrypted' => $row['is_encrypted'],
            'sentByUser' => $row['sender_id'] == $userId,
        ];
    }
    echo json_encode(['messages' => $messages]);
}
?>
