<?php
session_start();
require_once '../includes/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$id) {
    echo "Invalid Contact ID";
    exit;
}

// 1. Fetch Contact Details
// We join with Users table to get the names of who created it and who it is assigned to
$sql = "SELECT c.*, 
               u1.firstname as assign_fn, u1.lastname as assign_ln,
               u2.firstname as creator_fn, u2.lastname as creator_ln
        FROM Contacts c
        LEFT JOIN Users u1 ON c.assigned_to = u1.id
        LEFT JOIN Users u2 ON c.created_by = u2.id
        WHERE c.id = :id";

$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id]);
$contact = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$contact) {
    echo "Contact not found.";
    exit;
}

// 2. Fetch Notes for this contact
$noteSql = "SELECT n.comment, n.created_at, u.firstname, u.lastname 
            FROM Notes n 
            JOIN Users u ON n.created_by = u.id 
            WHERE n.contact_id = :id 
            ORDER BY n.created_at DESC";
$noteStmt = $conn->prepare($noteSql);
$noteStmt->execute([':id' => $id]);
$notes = $noteStmt->fetchAll(PDO::FETCH_ASSOC);

// Determine the formatted dates
$createdDate = date("F j, Y", strtotime($contact['created_at']));
$updatedDate = date("F j, Y", strtotime($contact['updated_at']));
?>

<div class="card contact-details-container">
    
    <div class="card-header">
        <div class="card-left">
            <div class="avatar">
                <span>👤</span>
            </div>
            <div class="contact-name">
                <h2><?= htmlspecialchars($contact['title'] . ' ' . $contact['firstname'] . ' ' . $contact['lastname']) ?></h2>
                <p class="contact-meta">
                    Created on <?= $createdDate ?> by <?= htmlspecialchars($contact['creator_fn'] . ' ' . $contact['creator_ln']) ?>
                    <br>
                    Updated on <?= $updatedDate ?>
                </p>
            </div>
        </div>
        
        <div class="contact-actions">
            <button class="btn-action btn-assign" id="btn-assign-to-me" data-id="<?= $contact['id'] ?>">
                🖐 Assign to me
            </button>
            <button class="btn-action btn-switch" id="btn-switch-type" data-id="<?= $contact['id'] ?>">
                ⇄ Switch to <?= $contact['type'] == 'Sales Lead' ? 'Support' : 'Sales Lead' ?>
            </button>
        </div>
    </div>

    <div class="contact-grid">
        <div>
            <label class="field-label">Email</label>
            <div><?= htmlspecialchars($contact['email']) ?></div>
        </div>
        <div>
            <label class="field-label">Telephone</label>
            <div><?= htmlspecialchars($contact['telephone']) ?></div>
        </div>
        <div>
            <label class="field-label">Company</label>
            <div><?= htmlspecialchars($contact['company']) ?></div>
        </div>
        <div>
            <label class="field-label">Assigned To</label>
            <div><?= htmlspecialchars($contact['assign_fn'] . ' ' . $contact['assign_ln']) ?></div>
        </div>
    </div>

    <h3>📝 Notes</h3>
    <div id="notes-list" class="notes-list">
        <?php foreach ($notes as $note): ?>
            <div class="note">
                <h4 class="note-author"><?= htmlspecialchars($note['firstname'] . ' ' . $note['lastname']) ?></h4>
                <p class="note-text"><?= htmlspecialchars($note['comment']) ?></p>
                <small class="note-time"><?= date("F j, Y \a\t g:ia", strtotime($note['created_at'])) ?></small>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="note-form">
        <h4>Add a note about <?= htmlspecialchars($contact['firstname']) ?></h4>
        <form id="add-note-form">
            <input type="hidden" name="contact_id" value="<?= $contact['id'] ?>">
            <textarea name="comment" required class="form-control"></textarea>
            <div class="text-right">
                <button type="submit" class="btn-primary">Add Note</button>
            </div>
        </form>
        <div id="note-msg"></div>
    </div>
</div>