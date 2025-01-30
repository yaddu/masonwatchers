<?php
// Database connection
$host = 'localhost';
$db = 'abhishek';
$user = 'root'; // Replace with your username if different
$pass = '';     // Replace with your password if set
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phonenumber'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmpassword'];

    // Validate password match
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.'); window.location.href = 'register.html';</script>";
        exit();
    }

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the database
    $stmt = $pdo->prepare('INSERT INTO users (full_name, email, phnumber, password_hash) VALUES (?, ?, ?, ?)');
    try {
        $stmt->execute([$fullName, $email, $phoneNumber, $passwordHash]);
        echo "<script>alert('Registration successful!'); window.location.href = 'login.html';</script>";
    } catch (\PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate email
            echo "<script>alert('Email already exists. Please use a different email.'); window.location.href = 'register.html';</script>";
        } else {
            die("Error: " . $e->getMessage());
        }
    }
}
?>
