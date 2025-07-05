<?php
session_start();
require_once '../config/db.php';

// Check admin login (basic)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch summary counts
$counts = [];
$tables = ['users', 'guides', 'packages', 'destinations', 'events', 'blogs', 'bookings', 'feedback', 'contact_messages'];

foreach ($tables as $table) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
    $counts[$table] = $stmt->fetchColumn();
}

// Fetch recent feedback (limit 5)
$feedbackStmt = $pdo->query("
    SELECT f.id, u.name AS user_name, f.rating, f.comment, f.submitted_at 
    FROM feedback f 
    LEFT JOIN users u ON f.user_id = u.id 
    ORDER BY f.submitted_at DESC LIMIT 5
");
$recentFeedbacks = $feedbackStmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard - TravelEase Mangaluru</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      min-height: 100vh;
      display: flex;
    }
    #sidebar {
      min-width: 250px;
      background-color: #b71c1c; /* deep red */
      color: white;
      min-height: 100vh;
    }
    #sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 12px 20px;
    }
    #sidebar a:hover {
      background-color: #7f0000;
      color: white;
    }
    #content {
      flex-grow: 1;
      padding: 20px;
      background: #f8f9fa;
    }
    .card {
      border-radius: 10px;
    }
    .card h5 {
      color: #b71c1c;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<nav id="sidebar">
  <div class="p-4">
    <h3 class="mb-4">TravelEase Admin</h3>
    <a href="dashboard.php">Dashboard</a>
    <a href="manage-guides.php">Manage Guides</a>
    <a href="manage-packages.php">Manage Packages</a>
    <a href="manage-destinations.php">Manage Destinations</a>
    <a href="manage-blogs.php">Manage Blogs</a>
    <a href="manage-events.php">Manage Events</a>
    <a href="view-feedback.php">View Feedback</a>
    <a href="view-contacts.php">View Contacts</a>
        <a href="bookings.php">View Bookings</a>
    <a href="logout.php" class="mt-4 d-block text-danger">Logout</a>
  </div>
</nav>

<!-- Main content -->
<div id="content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dashboard</h2>
    <div>Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></div>
  </div>

  <!-- Summary Cards -->
  <div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
      <div class="card shadow-sm p-3">
        <h5>Users</h5>
        <p class="fs-3"><?php echo $counts['users']; ?></p>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card shadow-sm p-3">
        <h5>Guides</h5>
        <p class="fs-3"><?php echo $counts['guides']; ?></p>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card shadow-sm p-3">
        <h5>Packages</h5>
        <p class="fs-3"><?php echo $counts['packages']; ?></p>
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="card shadow-sm p-3">
        <h5>Destinations</h5>
        <p class="fs-3"><?php echo $counts['destinations']; ?></p>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-5">
    <div class="col-md-4 col-sm-6">
      <div class="card shadow-sm p-3">
        <h5>Events</h5>
        <p class="fs-3"><?php echo $counts['events']; ?></p>
      </div>
    </div>
    <div class="col-md-4 col-sm-6">
      <div class="card shadow-sm p-3">
        <h5>Blogs</h5>
        <p class="fs-3"><?php echo $counts['blogs']; ?></p>
      </div>
    </div>
    <div class="col-md-4 col-sm-6">
      <div class="card shadow-sm p-3">
        <h5>Bookings</h5>
        <p class="fs-3"><?php echo $counts['bookings']; ?></p>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-5">
    <div class="col-md-6 col-sm-12">
      <div class="card shadow-sm p-3">
        <h5>Feedback Count</h5>
        <p class="fs-3"><?php echo $counts['feedback']; ?></p>
      </div>
    </div>
    <div class="col-md-6 col-sm-12">
      <div class="card shadow-sm p-3">
        <h5>Contact Messages</h5>
        <p class="fs-3"><?php echo $counts['contact_messages']; ?></p>
      </div>
    </div>
  </div>

  <!-- Recent Feedback Table -->
  <div class="card shadow-sm p-3">
    <h4>Recent Feedback</h4>
    <table class="table table-striped table-hover mt-3">
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Rating</th>
          <th>Comment</th>
          <th>Submitted At</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($recentFeedbacks): ?>
          <?php foreach ($recentFeedbacks as $fb): ?>
            <tr>
              <td><?php echo htmlspecialchars($fb['id']); ?></td>
              <td><?php echo htmlspecialchars($fb['user_name'] ?? 'Guest'); ?></td>
              <td><?php echo str_repeat('⭐', (int)$fb['rating']); ?></td>
              <td><?php echo htmlspecialchars(substr($fb['comment'], 0, 50)) . (strlen($fb['comment']) > 50 ? '...' : ''); ?></td>
              <td><?php echo date('d M Y, H:i', strtotime($fb['submitted_at'])); ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center">No feedback found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
