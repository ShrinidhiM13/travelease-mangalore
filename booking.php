
<?php
session_start();
include 'config/db.php'; // Make sure this provides $pdo as PDO connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$package_id = isset($_GET['package_id']) ? intval($_GET['package_id']) : 0;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $package_id = intval($_POST['package_id']);
    $pickup_location = trim($_POST['pickup_location']);

    if ($package_id <= 0 || empty($pickup_location)) {
        $message = '<div class="alert alert-danger">Please select a package and enter pickup location.</div>';
    } else {
        // Check if package exists
        $stmt = $pdo->prepare("SELECT id FROM packages WHERE id = ?");
        $stmt->execute([$package_id]);
        if ($stmt->rowCount() === 0) {
            $message = '<div class="alert alert-danger">Invalid package selected.</div>';
        } else {
            // Insert booking
            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, package_id, pickup_location) VALUES (?, ?, ?)");
            $success = $stmt->execute([$user_id, $package_id, $pickup_location]);
            if ($success) {
                $message = '<div class="alert alert-success">Booking successful! We will contact you soon.</div>';
            } else {
                $message = '<div class="alert alert-danger">Failed to place booking. Please try again.</div>';
            }
        }
    }
}

// Fetch package details for display
$package = null;
if ($package_id > 0) {
    $stmt = $pdo->prepare("SELECT p.*, d.name AS destination_name FROM packages p LEFT JOIN destinations d ON p.destination_id = d.id WHERE p.id = ?");
    $stmt->execute([$package_id]);
    $package = $stmt->fetch(PDO::FETCH_ASSOC);
}

include 'includes/nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Book Package - TravelEase Mangaluru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .booking-container {
            max-width: 600px;
            margin: 3rem auto;
            padding: 2rem;
            background: #111;
            border: 2px solid #c8102e;
            border-radius: 12px;
        }
        .btn-book {
            background-color: #c8102e;
            border: none;
        }
        .btn-book:hover {
            background-color: #a00d24;
        }
        .package-info {
            border-bottom: 1px solid #c8102e;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
        .package-info h4 {
            color: #c8102e;
        }
    </style>
</head>
<body>

<div class="booking-container">
    <h2 class="mb-4 text-center" style="color:#c8102e;">Book Your Package</h2>

    <?= $message ?>

    <?php if ($package): ?>
        <div class="package-info">
            <h4><?= htmlspecialchars($package['name']) ?></h4>
            <p><strong>Destination:</strong> <?= htmlspecialchars($package['destination_name'] ?? 'Unknown') ?></p>
            <p><strong>Price:</strong> ₹<?= number_format($package['price'], 2) ?></p>
            <p><strong>Duration:</strong> <?= intval($package['days']) ?> day(s)</p>
        </div>

        <form method="POST" action="booking.php" novalidate>
            <input type="hidden" name="package_id" value="<?= htmlspecialchars($package_id) ?>" />
            <div class="mb-3">
                <label for="pickup_location" class="form-label">Pickup Location *</label>
                <input type="text" class="form-control" id="pickup_location" name="pickup_location" placeholder="Enter your pickup location" required />
            </div>
            <button type="submit" class="btn btn-book w-100">Confirm Booking</button>
        </form>
    <?php else: ?>
        <p class="text-warning">No package selected. Please go back and select a package.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
