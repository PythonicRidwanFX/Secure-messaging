<?php
// Database connection details
$servername = "localhost";
$db_username = "root"; // Default username for XAMPP/WAMP
$db_password = ""; // Default password for XAMPP/WAMP
$dbname = "esther";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = $_POST['phone'];

    // Verify captcha
    if ($_POST['captcha'] !== "7") {
        die("Captcha verification failed.");
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert data
    $sql = "INSERT INTO users (fullname, username, email, password, phone)
            VALUES ('$fullname', '$username', '$email', '$hashed_password', '$phone')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Redirect to index.php after successful registration
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}

// Close connection
$conn->close();
?>
