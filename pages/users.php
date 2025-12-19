<?php
session_start();
require_once '../includes/db.php';

// SECURITY: Only allow Admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    echo '<div class="access-denied">Access Denied. You must be an Admin to view this page.</div>';
    exit;
}

// Fetch all users
$stmt = $conn->query("SELECT * FROM Users");
$all_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="dashboard-header">
    <h2>Users</h2>
    <button id="add-user-btn" class="btn-primary">+ Add User</button>
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($all_users as $user): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></strong>
                    </td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>