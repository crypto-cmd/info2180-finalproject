<?php
session_start();
require_once '../includes/db.php'; // Connect to the database

// If user is already logged in, go to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Check against database
    $stmt = $conn->prepare("SELECT * FROM Users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Success
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['lastname'] = $user['lastname'];
        $_SESSION['role'] = $user['role'];
        
        header("Location: ../index.php");
        exit;
    } else {
        $error = "Invalid email address or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dolphin CRM - Login</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="login-page">
    <div class="login-box">
        <h2>Dolphin CRM</h2>
        <h3>Login</h3>
        
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="email" name="email" placeholder="Email address" required class="form-control">
            <input type="password" name="password" placeholder="Password" required class="form-control">
            <button type="submit" class="btn-primary">Login</button>
        </form>
        
        <p class="login-footer">Copyright &copy; 2025 Dolphin CRM</p>
    </div>
</body>
</html>