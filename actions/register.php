<?php
session_start();
include '../config/db.php'; // path to your PDO connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        // Email exists: Show JS alert and go back
        echo "<script>
            alert('Email already exists. Please use another email.');
            window.history.back();
        </script>";
        exit;
    } else {
        // Insert user into DB
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->execute([$name, $email, $hashed_password]);

        $_SESSION['success'] = "Registration successful! Please login.";
        header('Location: ../login.php');
        exit;
    }
}
?>
