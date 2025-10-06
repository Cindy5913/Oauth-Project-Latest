<?php
session_start();
$usersFile = "users.json"; //where user data will be saved
//retrieve data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
//check if email already registered
    if (isset($users[$email])) {
        $_SESSION['message'] = "Email already registered. Please login.";
        header("Location: index.php");
        exit;
    } elseif ($password !== $confirm) {
        $_SESSION['message'] = "Passwords do not match.";
        header("Location: signup.php");
        exit;
    } else {
        $users[$email] = [
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT)//Creates a new user entry (with email and hashed password).
        ];
        file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

        $_SESSION['message'] = "Signup successful! Please login.";
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up</title>
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
  background: #10b981;
  color: white;
  transition: background 0.3s;
}
button:hover {
  background: #059669;
}
a.back {
  display: inline-block;
  margin-top: 15px;
  text-decoration: none;
  color: #ffd700;
  font-weight: 500;
  transition: color 0.3s;
}
a.back:hover {
  color: #fff176;
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
    if (isset($_SESSION['message'])) {
        echo "<p style='color:red; font-weight:bold;'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>
    <h2>Create Account</h2>
    <p>Sign up to get started 🚀</p>
    <form method="POST">
      <input type="email" name="email" placeholder="Enter Email" required>
      <input type="password" name="password" placeholder="Enter Password" required>
      <input type="password" name="confirm" placeholder="Confirm Password" required>
      <button type="submit">Sign Up</button>
    </form>
    <a href="index.php" class="back">Already have an account? Login</a>
  </div>
</body>
</html>
