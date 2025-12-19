<?php
session_start();
require_once '../includes/db.php';

// Fetch list of users for the "Assigned To" dropdown
$stmt = $conn->query("SELECT id, firstname, lastname FROM Users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="form-container">
    <h2>New Contact</h2>

    <form id="new-contact-form">
        <div class="form-grid-3">
            <div>
                <label>Title</label>
                <select name="title" class="form-control">
                    <option value="Mr">Mr</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Ms">Ms</option>
                    <option value="Dr">Dr</option>
                    <option value="Prof">Prof</option>
                </select>
            </div>
            <div>
                <label>First Name</label>
                <input type="text" name="firstname" class="form-control" required>
            </div>
            <div>
                <label>Last Name</label>
                <input type="text" name="lastname" class="form-control" required>
            </div>
        </div>

        <div class="form-grid-2">
            <div>
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div>
                <label>Telephone</label>
                <input type="text" name="telephone" class="form-control">
            </div>
        </div>

        <div class="form-grid-2">
            <div>
                <label>Company</label>
                <input type="text" name="company" class="form-control">
            </div>
            <div>
                <label>Type</label>
                <select name="type" class="form-control">
                    <option value="Sales Lead">Sales Lead</option>
                    <option value="Support">Support</option>
                </select>
            </div>
        </div>

        <div class="form-section">
            <label>Assigned To</label>
            <select name="assigned_to" class="form-control">
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>">
                        <?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="text-right">
            <button type="submit" class="btn-primary">Save</button>
        </div>
    </form>
    
    <div id="msg" class="form-msg"></div>
</div>