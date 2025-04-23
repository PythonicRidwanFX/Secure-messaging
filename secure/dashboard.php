<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "esther"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Encryption and decryption keys (Example: You should securely store these keys)
define('ENCRYPTION_KEY', 'your-encryption-key-here');  // Change this key to a secure one
define('METHOD', 'aes-256-cbc');

// Encryption function
function encryptMessage($message) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(METHOD));
    $encrypted = openssl_encrypt($message, METHOD, ENCRYPTION_KEY, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

// Decryption function
function decryptMessage($encryptedMessage) {
    list($encryptedData, $iv) = explode('::', base64_decode($encryptedMessage), 2);
    return openssl_decrypt($encryptedData, METHOD, ENCRYPTION_KEY, 0, $iv);
}

// Fetch users excluding the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT id, fullname FROM users WHERE id != '$user_id'"; // Fetch all users except the logged-in user
$result = $conn->query($sql);

$users = []; // Initialize the users array

if ($result->num_rows > 0) {
    // Loop through the result and populate the $users array
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Fetch messages for a specific contact
$messages = [];
if (isset($_GET['contact'])) {
    $contactName = $_GET['contact'];
    $stmt = $conn->prepare("
        SELECT m.message, m.is_encrypted, m.sender_id
        FROM messages m
        JOIN users u ON m.sender_id = u.id
        WHERE (m.sender_id = ? AND u.fullname = ?) OR (m.receiver_id = ? AND u.fullname = ?)
    ");
    $stmt->bind_param("isss", $user_id, $contactName, $user_id, $contactName);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $message = $row['message'];
        if ($row['is_encrypted']) {
            $message = decryptMessage($message); // Decrypt message if encrypted
        }
        $messages[] = [
            'message' => $message,
            'sentByUser' => $row['sender_id'] == $user_id
        ];
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Messaging App</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        header {
            background: #007bff;
            color: white;
            padding: 1rem;
            text-align: center;
        }

        .container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 25%;
            background: #f8f9fa;
            border-right: 1px solid #ddd;
            overflow-y: auto;
        }

        .sidebar h3 {
            margin: 1rem;
            font-size: 1.2rem;
        }

        .contact {
            padding: 1rem;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
        }

        .contact:hover {
            background: #e9ecef;
        }

        /* Chat Area */
        .chat-area {
            width: 75%;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            background: #007bff;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-messages {
            flex: 1;
            padding: 1rem;
            overflow-y: auto;
        }

        .message {
            margin-bottom: 1rem;
        }

        .message.sent {
            text-align: right;
        }

        .message .bubble {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 10px;
            max-width: 60%;
        }

        .message.received .bubble {
            background: #e9ecef;
        }

        .message.sent .bubble {
            background: #007bff;
            color: white;
        }

        /* Message Input */
        .chat-input {
            display: flex;
            padding: 0.5rem;
            background: #f8f9fa;
            border-top: 1px solid #ddd;
        }

        .chat-input input {
            flex: 1;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .chat-input button {
            margin-left: 0.5rem;
            padding: 0.5rem 1rem;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .chat-input button:hover {
            background: #0056b3;
        }

        .encryption-checkbox {
            margin-left: 1rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>Secure Messaging App</h1>
    </header>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h3>Users</h3>
            <?php
            // Check if users array is not empty
            if (!empty($users)) {
                foreach ($users as $user) {
                    echo '<div class="contact" onclick="loadChat(\'' . $user['id'] . '\', \'' . $user['fullname'] . '\')">' . $user['fullname'] . '</div>';
                }
            } else {
                echo '<p>No users available.</p>';
            }
            ?>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            <!-- Chat Header -->
            <div class="chat-header">
                <span id="chat-name">Select a contact</span>
                <a href="logout.php" style="color: white; text-decoration: none;">Logout</a>
            </div>
            <!-- Chat Messages -->
            <div class="chat-messages" id="chat-messages">
                <!-- Messages will be dynamically loaded here -->
                <?php
                // Display messages
                foreach ($messages as $msg) {
                    $messageClass = $msg['sentByUser'] ? 'sent' : 'received';
                    echo '<div class="message ' . $messageClass . '">
                            <div class="bubble">' . $msg['message'] . '</div>
                          </div>';
                }
                ?>
            </div>

            <!-- Message Input -->
            <form method="post" action="send_message.php">
                <div class="chat-input">
                    <input type="text" id="message-input" name="message" placeholder="Type a message...">
                    <label for="encrypt-checkbox" class="encryption-checkbox">
                        <input type="checkbox" id="encrypt-checkbox"> Encrypt
                    </label>
                    <button onclick="sendMessage()">Send</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // JavaScript functions for chat functionality
        function loadChat(userId, userName) {
            document.getElementById("chat-name").innerText = "Chat with " + userName;
            // Add functionality to load chat for selected user (userId)
            // Example: make an AJAX request to fetch messages with the selected userId
            // Implement the AJAX call here for dynamic chat loading
        }

        function sendMessage() {
            const input = document.getElementById("message-input");
            const messages = document.getElementById("chat-messages");
            const encryptCheckbox = document.getElementById("encrypt-checkbox");

            if (input.value.trim() !== "") {
                let message = input.value;
                const isEncrypted = encryptCheckbox.checked;

                if (isEncrypted) {
                    message = encryptMessage(message);
                }

                const messageBubble = document.createElement("div");
                messageBubble.classList.add("message", "sent");
                messageBubble.innerHTML = `<div class="bubble">${message}</div>`;
                messages.appendChild(messageBubble);

                // Send the message to the server (Add AJAX request here)
                input.value = "";  // Clear input field
                $.ajax({
                    url: "",
                    method: "POST",
                    data:{messages:messages},
                    success: function(){

                    }
                })
            }
        }

        function encryptMessage(message) {
            // Encrypt message here (example using base64 encoding for demonstration purposes)
            return btoa(message);
        }
    </script>
</body>
</html>
