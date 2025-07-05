<?php
session_start();
require_once '../config/db.php';

// Admin session check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$errors = [];
$success = '';

// Fetch all destinations for dropdown
$destinations = [];
try {
    $stmt = $pdo->query("SELECT id, name FROM destinations ORDER BY name ASC");
    $destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching destinations: " . $e->getMessage());
}

function uploadPackageImage($file) {
    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowed)) {
        return 'invalid_type';
    }
    if ($file['size'] > 3 * 1024 * 1024) {
        return 'too_large';
    }
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('package_', true) . '.' . $ext;
    $destination = __DIR__ . "/../uploads/packages/" . $filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return "uploads/packages/" . $filename;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = trim($_POST['name'] ?? '');
    $destination_id = trim($_POST['destination_id'] ?? '');
    $details = trim($_POST['details'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $days = trim($_POST['days'] ?? '');

    // Validation
    if ($name === '') $errors[] = "Name is required.";
    if ($destination_id === '' || !ctype_digit($destination_id)) $errors[] = "Valid destination is required.";
    if (!is_numeric($price) || $price < 0) $errors[] = "Price must be a non-negative number.";
    if (!ctype_digit($days) || intval($days) < 1) $errors[] = "Duration (days) must be a positive integer.";

    $image = null;
    if (isset($_FILES['image'])) {
        $uploadResult = uploadPackageImage($_FILES['image']);
        if ($uploadResult === 'invalid_type') {
            $errors[] = "Invalid image type. Only JPG, PNG, GIF allowed.";
        } elseif ($uploadResult === 'too_large') {
            $errors[] = "Image must be under 3MB.";
        } elseif ($uploadResult === false) {
            $errors[] = "Image upload failed.";
        } else {
            $image = $uploadResult;
        }
    }

    if (!$errors) {
        if ($id) {
            // Fetch current image if exists
            $stmt = $pdo->prepare("SELECT image FROM packages WHERE id = ?");
            $stmt->execute([$id]);
            $current = $stmt->fetch(PDO::FETCH_ASSOC);

            // Delete old image if new uploaded
            if ($image && $current && $current['image'] && file_exists(__DIR__ . "/../" . $current['image'])) {
                unlink(__DIR__ . "/../" . $current['image']);
            }

            $image = $image ?: ($current['image'] ?? null);

            $stmt = $pdo->prepare("UPDATE packages SET name=?, destination_id=?, details=?, price=?, days=?, image=? WHERE id=?");
            $stmt->execute([$name, $destination_id, $details, $price, $days, $image, $id]);
            $success = "Package updated successfully.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO packages (name, destination_id, details, price, days, image) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $destination_id, $details, $price, $days, $image]);
            $success = "New package added successfully.";
        }
    }
}

if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    $stmt = $pdo->prepare("SELECT image FROM packages WHERE id=?");
    $stmt->execute([$deleteId]);
    $package = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($package && $package['image'] && file_exists(__DIR__ . "/../" . $package['image'])) {
        unlink(__DIR__ . "/../" . $package['image']);
    }

    $stmt = $pdo->prepare("DELETE FROM packages WHERE id=?");
    $stmt->execute([$deleteId]);
    header("Location: manage-packages.php");
    exit;
}

// Fetch packages with joined destination name for display
$stmt = $pdo->query("SELECT p.*, d.name AS destination_name FROM packages p LEFT JOIN destinations d ON p.destination_id = d.id ORDER BY p.id DESC");
$packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Manage Packages - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
    .img-thumb {
        max-width: 100px;
        max-height: 100px;
        object-fit: cover;
        border-radius: 4px;
    }
</style>
</head>
<body>
    <?php include 'nav.php'; ?>
<div class="container my-4">
    <h2>Manage Packages</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul><?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?></ul>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="mb-4">
        <input type="hidden" name="id" id="packageId" />
        <div class="mb-3">
            <label for="name" class="form-label">Package Name *</label>
            <input type="text" name="name" id="name" class="form-control" required />
        </div>
        <div class="mb-3">
            <label for="destination_id" class="form-label">Destination *</label>
            <select name="destination_id" id="destination_id" class="form-select" required>
                <option value="">-- Select Destination --</option>
                <?php foreach ($destinations as $dest): ?>
                    <option value="<?= htmlspecialchars($dest['id']) ?>"><?= htmlspecialchars($dest['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="days" class="form-label">Duration (days) *</label>
            <input type="number" name="days" id="days" class="form-control" min="1" required />
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price (₹) *</label>
            <input type="number" name="price" id="price" class="form-control" min="0" step="0.01" required />
        </div>
        <div class="mb-3">
            <label for="details" class="form-label">Details</label>
            <textarea name="details" id="details" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image (JPG, PNG, GIF) max 3MB</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/jpeg,image/png,image/gif" />
        </div>
        <button type="submit" class="btn btn-primary">Save Package</button>
        <button type="reset" class="btn btn-secondary" onclick="clearForm()">Clear</button>
    </form>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Destination</th>
                <th>Duration (days)</th>
                <th>Price (₹)</th>
                <th>Image</th>
                <th>Details</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($packages as $package): ?>
            <tr>
                <td><?= htmlspecialchars($package['id']) ?></td>
                <td><?= htmlspecialchars($package['name']) ?></td>
                <td><?= htmlspecialchars($package['destination_name'] ?? 'Unknown') ?></td>
                <td><?= htmlspecialchars($package['days']) ?></td>
                <td><?= htmlspecialchars(number_format($package['price'], 2)) ?></td>
                <td>
                    <?php if ($package['image']): ?>
                        <img src="../<?= htmlspecialchars($package['image']) ?>" alt="Package Image" class="img-thumb" />
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
                <td><?= nl2br(htmlspecialchars($package['details'])) ?></td>
                <td>
                    <a href="?delete=<?= intval($package['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this package?');">Delete</a>
                    <button class="btn btn-sm btn-warning" onclick='editPackage(<?= json_encode($package) ?>)'>Edit</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function editPackage(pkg) {
    document.getElementById('packageId').value = pkg.id;
    document.getElementById('name').value = pkg.name;
    document.getElementById('destination_id').value = pkg.destination_id;
    document.getElementById('days').value = pkg.days;
    document.getElementById('price').value = pkg.price;
    document.getElementById('details').value = pkg.details;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function clearForm() {
    document.getElementById('packageId').value = '';
}
</script>
</body>
</html>
