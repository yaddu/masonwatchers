<?php
// Database connection
$host = 'localhost';
$db = 'abhishek';
$user = 'root'; // Replace with your database username
$pass = '';     // Replace with your database password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if the user exists
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Verify the password
        if (password_verify($password, $user['password_hash'])) {
            // Login successful
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['full_name'] = $user['full_name']; // If this column exists
            header("Location: dashboard.html");
            exit;
        } else {
            // Invalid password
            echo "<script>alert('Invalid password. Please try again.'); window.location.href = 'login.html';</script>";
        }
    } else {
        // User not found
        echo "<script>alert('No account found with this email. Please register.'); window.location.href = 'register.html';</script>";
    }
}
?>
