<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Secure Messaging Application</title>
  <style>
    /* General Styling */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #1a3c66; /* Dark blue background */
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    /* Login Container */
    .login-container {
      background-color: #2a5b96; /* Lighter blue for the form background */
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
      text-align: center;
      width: 300px;
    }

    /* Heading */
    .login-container h1 {
      color: white;
      font-size: 24px;
      margin-bottom: 10px;
    }

    .login-container p {
      color: #dcdcdc; /* Light gray text */
      margin-bottom: 20px;
      font-size: 14px;
    }

    /* Form Inputs */
    .form-group {
      margin-bottom: 15px;
    }

    .form-control {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .form-control:focus {
      outline: none;
      border-color: #4a90e2; /* Highlight color on focus */
      box-shadow: 0 0 5px rgba(74, 144, 226, 0.5);
    }

    /* Button */
    .btn {
      width: 100%;
      padding: 10px;
      background-color: #004080;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: #00509e;
    }

    /* Register Link */
    .register-link {
      margin-top: 15px;
      font-size: 14px;
    }

    .register-link a {
      color: #dcdcdc;
      text-decoration: none;
    }

    .register-link a:hover {
      color: white;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h1>Secure Messaging Application</h1>
    <p>Your privacy, our priority.</p>
    <form method="POST" action="login_process.php">
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Username" required name="username">
      </div>
      <div class="form-group">
        <input type="password" class="form-control" placeholder="Password" required name="password">
      </div>
      <button type="submit" class="btn">Login</button>
    </form>
    <div class="register-link">
      <p>Don't have an account? <a href="register.php">Register Now</a></p>
    </div>
  </div>
  <script>
        // Fetch users from the server
        fetch('fetch_users.php')
            .then(response => response.json())
            .then(users => {
                const userSelect = document.getElementById('user-select');
                userSelect.innerHTML = '<option value="">Select a user</option>';

                users.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.username;
                    option.textContent = `${user.fullname} (${user.username})`;
                    userSelect.appendChild(option);
                });

                // Update hidden username field on selection
                userSelect.addEventListener('change', () => {
                    document.getElementById('username').value = userSelect.value;
                });
            })
            .catch(error => {
                console.error('Error fetching users:', error);
                const userSelect = document.getElementById('user-select');
                userSelect.innerHTML = '<option value="">Failed to load users</option>';
            });
    </script>
</body>
</html>
