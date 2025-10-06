<?php
session_start();
require "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login Page</title>
  <style>
    body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: url('https://plus.unsplash.com/premium_photo-1679830513990-82a4280f41b4?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')
              no-repeat center center fixed;
  background-size: cover;
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}
    .container {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
      text-align: center;
      width: 380px;
      animation: fadeIn 0.6s ease-in-out;
      color: #fff;
      border: 2px solid rgba(255, 255, 255, 0.3);
    }
    h2 {
      margin-bottom: 10px;
      color: #fff;
    }
    p {
      color: #f1f1f1;
      margin-bottom: 25px;
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    input {
      padding: 12px;
      border: 1px solid rgba(255, 255, 255, 0.4);
      background: rgba(255, 255, 255, 0.1);
      border-radius: 8px;
      font-size: 15px;
      color: #fff;
      outline: none;
    }
    input::placeholder {
      color: #ddd;
    }
    button {
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.3s, box-shadow 0.3s;
    }
    button.login-btn {
      background: linear-gradient(135deg, #3b82f6, #2563eb);
      color: white;
      box-shadow: 0 0 10px rgba(59, 130, 246, 0.6);
    }
    button.login-btn:hover {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      box-shadow: 0 0 18px rgba(59, 130, 246, 0.9);
    }
    a.google-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      background: white;
      color: #444;
      padding: 12px 18px;
      border-radius: 8px;
      border: 1px solid #ddd;
      text-decoration: none;
      font-weight: 500;
      font-size: 15px;
      transition: box-shadow 0.3s;
    }
    a.google-btn:hover {
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }
    a.google-btn img {
      width: 20px;
      height: 20px;
      margin-right: 10px;
    }
    a.logout {
      display: inline-block;
      background: #d9534f;
      color: white;
      padding: 10px 18px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 500;
      margin-top: 15px;
      transition: background 0.3s;
    }
    a.logout:hover {
      background: #c9302c;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="container">
    <?php
    // Alert message
    if (isset($_SESSION['message'])) {
        echo "<script>alert('" . $_SESSION['message'] . "');</script>";
        unset($_SESSION['message']);
    }

    // User logged in?
    if (isset($_SESSION['user'])) {
        echo "<h2>Welcome, " . htmlspecialchars($_SESSION['user']['name']) . " 👋</h2>";
        echo "<p>Email: " . htmlspecialchars($_SESSION['user']['email']) . "</p>";
        echo "<a href='logout.php' class='logout'>Logout</a>";//user is stored in the session, means the user is logged in.
    } else {
        $auth_url = AUTH_URL . "?response_type=code&client_id=" . CLIENT_ID .
                    "&redirect_uri=" . urlencode(REDIRECT_URI) .//prepares the Google login link to redirect users to Google’s login page.
                    "&scope=email%20profile&access_type=online";

        echo "<h2>Login Required</h2>";
        echo "<p>Sign in with your account:</p>";
        echo '<form method="POST" action="login_process.php">
                <input type="email" name="email" placeholder="Enter Email" required>
                <input type="password" name="password" placeholder="Enter Password" required>
                <button type="submit" class="login-btn">Login</button>
                <p>Don\'t have an account? <a href="signup.php" style="color:#ffd700;">Sign Up</a></p>
              </form>';
        echo "<hr style='margin:20px 0; border-color: rgba(255,255,255,0.3);'>";
        echo "<a href='$auth_url' class='google-btn'>
                <img src='https://www.gstatic.com/images/branding/product/1x/gsa_64dp.png' alt='Google Logo'>
                Sign in with Google
              </a>";
    }
    ?>
  </div>
</body>
</html>
