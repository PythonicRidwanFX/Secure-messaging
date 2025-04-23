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

        /* Encryption Toggle */
        .encryption-toggle {
            padding: 0.5rem;
            background: #f8f9fa;
            border-top: 1px solid #ddd;
            text-align: center;
        }
        .encryption-toggle label {
            margin-right: 0.5rem;
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
            <h3>Contacts</h3>
            <div class="contact" onclick="loadChat('John Doe')">
                John Doe
            </div>
            <div class="contact" onclick="loadChat('Jane Smith')">
                Jane Smith
            </div>
            <div class="contact" onclick="loadChat('Alex Johnson')">
                Alex Johnson
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            <!-- Chat Header -->
            <div class="chat-header">
                <span id="chat-name">Select a Contact</span>
            </div>

            <!-- Chat Messages -->
            <div class="chat-messages" id="chat-messages">
                <!-- Messages will be dynamically loaded here -->
            </div>

            <!-- Encryption Toggle -->
            <div class="encryption-toggle">
                <label>
                    <input type="checkbox" id="encryption-toggle">
                    Encrypt Messages
                </label>
            </div>

            <!-- Chat Input -->
            <div class="chat-input">
                <input type="text" id="message-input" placeholder="Type a message...">
                <button onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>

    <script>
        // Caesar Cipher for encryption/decryption
        const shift = 3; // Encryption shift value
        function encrypt(text) {
            return text.replace(/[a-zA-Z]/g, (char) => {
                const base = char <= 'Z' ? 'A'.charCodeAt(0) : 'a'.charCodeAt(0);
                return String.fromCharCode(((char.charCodeAt(0) - base + shift) % 26) + base);
            });
        }

        function decrypt(text) {
            return text.replace(/[a-zA-Z]/g, (char) => {
                const base = char <= 'Z' ? 'A'.charCodeAt(0) : 'a'.charCodeAt(0);
                return String.fromCharCode(((char.charCodeAt(0) - base - shift + 26) % 26) + base);
            });
        }

        // Dynamic chat loading
        function loadChat(contactName) {
            document.getElementById("chat-name").innerText = contactName;
            const messages = document.getElementById("chat-messages");
            messages.innerHTML = `<p>Chat with ${contactName} loaded.</p>`;
        }

        // Send message with encryption toggle
        function sendMessage() {
            const input = document.getElementById("message-input");
            const messages = document.getElementById("chat-messages");
            const encryptToggle = document.getElementById("encryption-toggle").checked;

            if (input.value.trim() !== "") {
                const messageBubble = document.createElement("div");
                messageBubble.classList.add("message", "sent");

                let messageText = input.value;
                if (encryptToggle) {
                    messageText = encrypt(messageText);
                }

                messageBubble.innerHTML = `<div class="bubble">${messageText}</div>`;
                messages.appendChild(messageBubble);
                input.value = "";

                // Scroll to the bottom of the chat
                messages.scrollTop = messages.scrollHeight;
            }
        }
    </script>
</body>
</html>
