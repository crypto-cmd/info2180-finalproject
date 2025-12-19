<?php
session_start();
// SECURITY: Only allow Admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    echo 'Access Denied.';
    exit;
}
?>

<div class="form-container">
    <h2>New User</h2>

    <form id="new-user-form">
        <div class="form-grid-2">
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
                <label>Password</label>
                <input type="password" name="password" class="form-control" required 
                       placeholder="At least 8 chars, 1 number, 1 capital">
            </div>
        </div>

        <div class="form-section">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="Member">Member</option>
                <option value="Admin">Admin</option>
            </select>
        </div>

        <div class="text-right">
            <button type="submit" class="btn-primary">Save</button>
        </div>
    </form>
    
    <div id="msg" class="form-msg"></div>
</div>