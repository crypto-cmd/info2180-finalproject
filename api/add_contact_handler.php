<?php
session_start();
require_once '../includes/db.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Sanitize Inputs
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $company = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $assigned_to = filter_input(INPUT_POST, 'assigned_to', FILTER_SANITIZE_NUMBER_INT);
    
    // Get the ID of the logged-in user who is creating this contact
    $created_by = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; 
    // Fallback to ID 1 (Admin) if session is missing, to prevent errors

    // 2. Insert into Database
    try {
        $sql = "INSERT INTO Contacts 
                (title, firstname, lastname, email, telephone, company, type, assigned_to, created_by, created_at, updated_at) 
                VALUES 
                (:title, :fname, :lname, :email, :tel, :company, :type, :assigned, :creator, NOW(), NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':fname' => $firstname,
            ':lname' => $lastname,
            ':email' => $email,
            ':tel' => $telephone,
            ':company' => $company,
            ':type' => $type,
            ':assigned' => $assigned_to,
            ':creator' => $created_by
        ]);

        echo "Success"; 
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>