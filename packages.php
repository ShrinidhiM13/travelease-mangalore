<?php
session_start();
include 'config/db.php';
include 'includes/nav.php';

// Fetch all packages with destination info using PDO
try {
    $stmt = $pdo->prepare("SELECT p.*, d.name as destination_name 
                           FROM packages p 
                           LEFT JOIN destinations d ON p.destination_id = d.id 
                           ORDER BY p.name ASC");
    $stmt->execute();
    $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching packages: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Packages - TravelEase Mangaluru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #000;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .package-card {
            background: #111;
            border: 2px solid #c8102e;
            border-radius: 12px;
            transition: transform 0.3s ease;
        }
        .package-card:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px #c8102e;
        }
        .package-img {
            height: 180px;
            object-fit: cover;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .btn-book {
            background-color: #c8102e;
            border: none;
        }
        .btn-book:hover {
            background-color: #a00d24;
        }
        .destination-name {
            color: #c8102e;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <h1 class="mb-4 text-center" style="color:#c8102e;">Travel Packages</h1>
    <div class="row g-4">
        <?php if (!empty($packages)): ?>
            <?php foreach ($packages as $package): ?>
                <div class="col-md-4">
                    <div class="package-card">
                        <img src="<?= htmlspecialchars($package['image'] ?: 'assets/images/default-package.jpg') ?>" alt="<?= htmlspecialchars($package['name']) ?>" class="package-img w-100" />
                        <div class="p-3">
                            <h4><?= htmlspecialchars($package['name']) ?></h4>
                            <p class="destination-name"><?= htmlspecialchars($package['destination_name'] ?? 'Unknown Destination') ?></p>
                            <p><?= nl2br(htmlspecialchars(substr($package['details'], 0, 120))) ?>...</p>
                            <p><strong>Price:</strong> ₹<?= number_format($package['price'], 2) ?></p>
                            <p><strong>Duration:</strong> <?= intval($package['days']) ?> day(s)</p>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="booking.php?package_id=<?= $package['id'] ?>" class="btn btn-book w-100">Book Now</a>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-book w-100">Login to Book</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No packages available at the moment. Please check back later.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
