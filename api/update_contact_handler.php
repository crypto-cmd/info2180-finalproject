<?php
session_start();
require_once '../includes/db.php';

// Check if valid POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contact_id = filter_input(INPUT_POST, 'contact_id', FILTER_SANITIZE_NUMBER_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $current_user_id = $_SESSION['user_id'];

    if (!$contact_id || !$action) {
        echo "Error: Missing ID or Action";
        exit;
    }

    try {
        if ($action === 'assign_to_me') {
            // Assign the contact to the currently logged-in user
            $sql = "UPDATE Contacts 
                    SET assigned_to = :uid, updated_at = NOW() 
                    WHERE id = :cid";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':uid' => $current_user_id, ':cid' => $contact_id]);
            echo "Success";
        } 
        elseif ($action === 'switch_type') {
            // Toggle between 'Sales Lead' and 'Support'
            $sql = "UPDATE Contacts 
                    SET type = CASE 
                        WHEN type = 'Sales Lead' THEN 'Support' 
                        ELSE 'Sales Lead' 
                    END, 
                    updated_at = NOW() 
                    WHERE id = :cid";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':cid' => $contact_id]);
            echo "Success";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>