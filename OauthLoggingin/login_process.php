<?php
// 1. Start session & load users
session_start();
$usersFile = "users.json";
// Load existing users from JSON file, or empty array if file not found
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

// 2. Handle form submission (POST only)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // 3. Check if email exists
    if (isset($users[$email])) {

        // 4. Verify password
        if (password_verify($password, $users[$email]['password'])) {
            // Save user data in session
            $_SESSION['user'] = [
                "name" => explode("@", $email)[0], // use part before @ as name
                "email" => $email
            ];
            $_SESSION['message'] = "Successfully logged in!";
            // Redirect to dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            // Wrong password
            $_SESSION['message'] = "Invalid password.";
            header("Location: index.php");
            exit;
        }

    } else {
        // 5. Email not found
        $_SESSION['message'] = "Email not registered. Please sign up first.";
        header("Location: index.php");
        exit;
    }

// 6. Fallback (non-POST request)
} else {
    header("Location: index.php");
    exit;
}
?>
