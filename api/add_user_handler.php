<?php
session_start();
require_once '../includes/db.php';

// Only Admin can add users
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    echo "Error: Access Denied";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Do not sanitize yet, we need to check length/chars
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Regex Check: 8 chars, 1 number, 1 capital letter [cite: 37]
    // ^ = start, (?=.*[A-Z]) = at least one Capital, (?=.*\d) = at least one digit, .{8,} = at least 8 chars
    $passwordRegex = '/^(?=.*[A-Z])(?=.*\d).{8,}$/';

    if (!preg_match($passwordRegex, $password)) {
        echo "Error: Password must be at least 8 characters, contain at least one number, and one capital letter.";
        exit;
    }

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM Users WHERE email = :email");
    $check->execute([':email' => $email]);
    if ($check->rowCount() > 0) {
        echo "Error: User with this email already exists.";
        exit;
    }

    // Hash Password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO Users (firstname, lastname, password, email, role, created_at) VALUES (:fname, :lname, :pass, :email, :role, NOW())");
        $stmt->execute([
            ':fname' => $firstname,
            ':lname' => $lastname,
            ':pass' => $hashed_password,
            ':email' => $email,
            ':role' => $role
        ]);
        echo "Success";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>