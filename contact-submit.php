<?php
session_start();
require_once 'config/db.php';  // Your PDO connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $errors = [];

    if ($name === '') {
        $errors[] = "Name is required.";
    }

    if ($email === '') {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    if ($message === '') {
        $errors[] = "Message cannot be empty.";
    }

    // Prevent header injection or suspicious input
    function has_header_injection($str) {
        return preg_match("/[\r\n]/", $str);
    }
    if (has_header_injection($name) || has_header_injection($email)) {
        $errors[] = "Invalid input detected.";
    }

    if ($errors) {
        $_SESSION['contact_errors'] = $errors;
        $_SESSION['old'] = ['name' => htmlspecialchars($name), 'email' => htmlspecialchars($email), 'message' => htmlspecialchars($message)];
        header("Location: contact.php");
        exit;
    }

    try {
        // Insert message into contacts table
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message, submitted_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$name, $email, $message]);

        $_SESSION['contact_success'] = "Thank you for contacting us! We'll get back to you shortly.";
        unset($_SESSION['old']);
    } catch (PDOException $e) {
        // Optional: log error for debugging: error_log($e->getMessage());
        $_SESSION['contact_errors'] = ["Sorry, there was an error saving your message. Please try again later."];
        $_SESSION['old'] = ['name' => htmlspecialchars($name), 'email' => htmlspecialchars($email), 'message' => htmlspecialchars($message)];
    }

    header("Location: contact.php");
    exit;
}

header("Location: contact.php");
exit;
