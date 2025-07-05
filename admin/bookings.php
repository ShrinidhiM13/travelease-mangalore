<?php
session_start();
require_once '../config/db.php';

// Redirect if not admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch all bookings with user and package info
try {
    $stmt = $pdo->query("
        SELECT 
            b.id AS booking_id,
            b.pickup_location,
            b.booking_date,
            u.id AS user_id,
            u.name AS user_name,
            u.email AS user_email,
            p.name AS package_name,
            p.price AS package_price
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN packages p ON b.package_id = p.id
        ORDER BY b.booking_date DESC
    ");
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching bookings: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard - Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {

            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            margin-top: 3rem;
        }
        table {
            background: #111;
        }
        th, td {
            vertical-align: middle !important;
        }
        th {
            color: #c8102e;
        }
        tbody tr:hover {
            background-color:rgb(255, 255, 255);
        }
    </style>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="container">
    <h2 class="mb-4 text-center" style="color:#c8102e;">All Bookings</h2>
    
    <?php if (!empty($bookings)): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-white">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>Package Name</th>
                    <th>Price (₹)</th>
                    <th>Pickup Location</th>
                    <th>Booking Date & Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><?= htmlspecialchars($b['booking_id']) ?></td>
                        <td><?= htmlspecialchars($b['user_name']) ?></td>
                        <td><?= htmlspecialchars($b['user_email']) ?></td>
                        <td><?= htmlspecialchars($b['package_name']) ?></td>
                        <td><?= number_format($b['package_price'], 2) ?></td>
                        <td><?= htmlspecialchars($b['pickup_location']) ?></td>
                        <td><?= htmlspecialchars(date("d M Y, H:i", strtotime($b['booking_date']))) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <p class="text-center">No bookings found.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
