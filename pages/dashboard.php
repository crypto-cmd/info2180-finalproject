<?php
session_start();
require_once '../includes/db.php';

// 1. Determine the Filter
$filter = filter_input(INPUT_GET, 'filter', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$data = [];

// 2. Build the Query based on the filter
if ($filter === 'Sales Lead') {
    $sql = "SELECT * FROM Contacts WHERE type = 'Sales Lead'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
} 
elseif ($filter === 'Support') {
    $sql = "SELECT * FROM Contacts WHERE type = 'Support'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
} 
elseif ($filter === 'Assigned to me') {
    $sql = "SELECT * FROM Contacts WHERE assigned_to = :uid";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':uid' => $user_id]);
} 
else {
    // Default: Show All
    $sql = "SELECT * FROM Contacts";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="dashboard-header">
    <h2>Dashboard</h2>
    <button id="nav-new-contact-btn" class="btn-primary">+ Add Contact</button>
</div>

<div class="filter-section">
    <strong>Filter By:</strong> 
    
    <a href="dashboard.php?filter=All" class="filter-btn <?= ($filter == '' || $filter == 'All') ? 'active' : '' ?>">All</a>
    
    <a href="dashboard.php?filter=Sales Lead" class="filter-btn <?= ($filter == 'Sales Lead') ? 'active' : '' ?>">Sales Leads</a>
    
    <a href="dashboard.php?filter=Support" class="filter-btn <?= ($filter == 'Support') ? 'active' : '' ?>">Support</a>
    
    <a href="dashboard.php?filter=Assigned to me" class="filter-btn <?= ($filter == 'Assigned to me') ? 'active' : '' ?>">Assigned to me</a>
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Company</th>
                <th>Type</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($contact['title'] . ' ' . $contact['firstname'] . ' ' . $contact['lastname']) ?></strong>
                    </td>
                    <td><?= htmlspecialchars($contact['email']) ?></td>
                    <td><?= htmlspecialchars($contact['company']) ?></td>
                    <td>
                        <?php if ($contact['type'] == 'Sales Lead'): ?>
                            <span class="badge-sales">SALES LEAD</span>
                        <?php else: ?>
                            <span class="badge-support">SUPPORT</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="view_contact.php?id=<?= $contact['id'] ?>" class="view-link">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <?php if (empty($contacts)): ?>
        <p class="empty-state">No contacts found for this filter.</p>
    <?php endif; ?>
</div>