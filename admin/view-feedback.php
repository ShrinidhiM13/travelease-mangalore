<?php
session_start();
require_once '../config/db.php';

// Admin session check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch feedback with user info
$stmt = $pdo->query("
    SELECT f.*, u.name as user_name
    FROM feedback f
    LEFT JOIN users u ON f.user_id = u.id
    ORDER BY f.submitted_at DESC
");
$feedbacks = $stmt->fetchAll();

function renderStars($rating) {
    return str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>View Feedback - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
    .star-rating {
        color: #ffbf00;
        font-size: 1.2rem;
    }
</style>
</head>
<body>
    <?php include 'nav.php'; ?>
<div class="container my-4">
    <h2>User Feedback</h2>
    <?php if (count($feedbacks) === 0): ?>
        <div class="alert alert-info">No feedback found.</div>
    <?php else: ?>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Submitted At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($feedbacks as $i => $fb): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($fb['user_name'] ?? 'Guest') ?></td>
                <td class="star-rating"><?= renderStars(intval($fb['rating'])) ?></td>
                <td><?= nl2br(htmlspecialchars($fb['comment'])) ?></td>
                <td><?= $fb['submitted_at'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
</body>
</html>
