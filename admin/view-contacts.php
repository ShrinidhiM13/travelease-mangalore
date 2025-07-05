<?php
session_start();
require_once '../config/db.php';

// Admin session check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch contact messages
$stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
$contacts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>View Contact Messages - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <?php include 'nav.php'; ?>
<div class="container my-4">
    <h2>Contact Messages</h2>
    <?php if (count($contacts) === 0): ?>
        <div class="alert alert-info">No contact messages found.</div>
    <?php else: ?>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Submitted At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $i => $contact): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($contact['name']) ?></td>
                <td><?= htmlspecialchars($contact['email']) ?></td>
                <td><?= nl2br(htmlspecialchars($contact['message'])) ?></td>
                <td><?= $contact['submitted_at'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
</body>
</html>
