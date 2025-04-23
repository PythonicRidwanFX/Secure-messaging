<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Secure Messaging System - Register</title>

  <!-- Font Awesome for Icons -->
  <script src="https://kit.fontawesome.com/4733528720.js" crossorigin="anonymous"></script>

  <!-- CSS Frameworks -->
  <link rel="stylesheet" href="vendors/css/bootstrap.min.css">
  <link rel="stylesheet" href="vendors/css/normalize.css">
  <link rel="stylesheet" href="resources/css/index.css">

  <style>
    body {
      background-color: #f5f7fa;
      font-family: 'Arial', sans-serif;
    }

    .container {
      max-width: 400px;
      margin: 50px auto;
      padding: 20px;
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-control {
      border-radius: 5px;
      height: 45px;
    }

    .btn-primary {
      width: 100%;
      border-radius: 5px;
      height: 45px;
      background-color: #0056b3;
      color: #fff;
      border: none;
    }

    .btn-primary:hover {
      background-color: #003d80;
    }

    small a {
      color: #0056b3;
    }

    small a:hover {
      text-decoration: underline;
    }

    .form-check-label {
      font-size: 0.9rem;
    }

    .captcha {
      text-align: center;
      font-weight: bold;
      font-size: 1.2rem;
      background-color: #f0f0f0;
      padding: 5px;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Register</h1>
    <form action="register_process.php" method="POST">

      <!-- Full Name -->
      <div class="form-group">
        <label for="fullname">Full Name</label>
        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter your full name" required>
      </div>

      <!-- Username -->
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Choose a username" required>
      </div>

      <!-- Email Address -->
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
      </div>

      <!-- Password -->
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
      </div>

      <!-- Confirm Password -->
      <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
      </div>

      <!-- Phone Number -->
      <div class="form-group">
        <label for="phone">Phone Number (Optional)</label>
        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number">
      </div>

      <!-- Captcha Verification -->
      <div class="form-group">
        <label for="captcha">Captcha Verification</label>
        <div class="captcha" id="captcha-text">3 + 4 = ?</div>
        <input type="text" class="form-control mt-2" id="captcha" name="captcha" placeholder="Enter the result" required>
      </div>

      <!-- Terms and Conditions -->
      <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
        <label class="form-check-label" for="terms">I agree to the <a href="#">Terms and Conditions</a>.</label>
      </div>

      <!-- Register Button -->
      <button type="submit" class="btn btn-primary" name="register">Register</button>
    </form>

    <!-- Redirect to Login -->
    <div class="text-center mt-3">
      <small>Already have an account? <a href="index.php">Login</a></small>
    </div>
  </div>

  <script>
    // Simple Captcha Validation
    document.querySelector('form').addEventListener('submit', function (e) {
      const captchaAnswer = document.getElementById('captcha').value;
      if (captchaAnswer.trim() !== "7") {
        alert("Captcha validation failed. Please try again.");
        e.preventDefault();
      }
      document.querySelector('form').addEventListener('submit', function (e) {
  const password = document.getElementById('password').value;
  const confirmPassword = document.getElementById('confirm_password').value;

  // Check if password and confirm password match
  if (password !== confirmPassword) {
    alert("Passwords do not match. Please check and try again.");
    e.preventDefault();
    return;
  }

  // Simple Captcha Validation
  const captchaAnswer = document.getElementById('captcha').value;
  if (captchaAnswer.trim() !== "7") {
    alert("Captcha validation failed. Please try again.");
    e.preventDefault();
  }
});

    });
  </script>

</body>
</html>
