<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h1>
    <a href="logout.php">Logout</a>
</body>
</html>
