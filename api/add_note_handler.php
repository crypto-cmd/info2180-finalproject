<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contact_id = filter_input(INPUT_POST, 'contact_id', FILTER_SANITIZE_NUMBER_INT);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $created_by = $_SESSION['user_id'];

    if ($contact_id && $comment) {
        try {
            // Insert Note
            $stmt = $conn->prepare("INSERT INTO Notes (contact_id, comment, created_by, created_at) VALUES (:cid, :comment, :uid, NOW())");
            $stmt->execute([
                ':cid' => $contact_id,
                ':comment' => $comment,
                ':uid' => $created_by
            ]);
            
            // Update Contact's "updated_at" time
            $updateStmt = $conn->prepare("UPDATE Contacts SET updated_at = NOW() WHERE id = :cid");
            $updateStmt->execute([':cid' => $contact_id]);

            echo "Success";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error: Missing data";
    }
}
?>