<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: pages/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dolphin CRM</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="assets/js/scripts.js" defer></script>
</head>
<body>

    <header>
        <img src="assets/images/logo.jpg" alt="Logo"> 
        <h1>Dolphin CRM</h1>
    </header>

    <div class="container">
        <aside>
            <nav>
                <ul>
                    <li><a href="#" id="nav-home">Home</a></li>
                    <li><a href="#" id="nav-new-contact">New Contact</a></li>
                    <li><a href="#" id="nav-users">Users</a></li>
                    <hr>
                    <li><a href="pages/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main>
            <div id="result">
                <h2>Dashboard</h2>
                <p>Welcome to Dolphin CRM.</p>
            </div>
        </main>
    </div>

    <footer class="site-footer">
        Copyright &copy; 2025 Dolphin CRM
    </footer>

</body>

</html>