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

function uploadGuidePhoto($file) {
    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if (!in_array($file['type'], $allowed)) {
        return 'invalid_type';
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        return 'too_large';
    }
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('guide_', true) . '.' . $ext;
    $destination = "../uploads/guides/" . $filename;
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return "uploads/guides/" . $filename;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = trim($_POST['name'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $experience = trim($_POST['experience'] ?? '');

    if ($name === '') $errors[] = "Name is required.";

    $photo = null;
    if (isset($_FILES['photo'])) {
        $uploadResult = uploadGuidePhoto($_FILES['photo']);
        if ($uploadResult === 'invalid_type') {
            $errors[] = "Invalid photo type. Only JPG, PNG, GIF allowed.";
        } elseif ($uploadResult === 'too_large') {
            $errors[] = "Photo must be under 2MB.";
        } elseif ($uploadResult === false) {
            $errors[] = "Photo upload failed.";
        } else {
            $photo = $uploadResult;
        }
    }

    if (!$errors) {
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM guides WHERE id = ?");
            $stmt->execute([$id]);
            $current = $stmt->fetch();

            $photo = $photo ?: $current['photo'];
            $stmt = $pdo->prepare("UPDATE guides SET name=?, contact=?, experience=?, photo=? WHERE id=?");
            $stmt->execute([$name, $contact, $experience, $photo, $id]);
            $success = "Guide updated successfully.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO guides (name, contact, experience, photo) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $contact, $experience, $photo]);
            $success = "New guide added successfully.";
        }
    }
}

if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM guides WHERE id=?");
    $stmt->execute([$deleteId]);
    header("Location: manage-guides.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM guides ORDER BY id DESC");
$guides = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Guides - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .img-thumb { max-width: 80px; max-height: 80px; object-fit: cover; border-radius: 4px; }
</style>
</head>
<body>
    <?php include 'nav.php'; ?>
<div class="container my-4">
    <h2>Manage Guides</h2>

    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($errors): ?><div class="alert alert-danger">
        <ul><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul>
    </div><?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="mb-4">
        <input type="hidden" name="id" id="guideId">
        <div class="mb-3">
            <label for="name" class="form-label">Name *</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="contact" class="form-label">Contact</label>
            <input type="text" name="contact" id="contact" class="form-control">
        </div>
        <div class="mb-3">
            <label for="experience" class="form-label">Experience</label>
            <textarea name="experience" id="experience" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="photo" class="form-label">Photo</label>
            <input type="file" name="photo" id="photo" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Save Guide</button>
        <button type="reset" class="btn btn-secondary">Clear</button>
    </form>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr><th>ID</th><th>Name</th><th>Contact</th><th>Experience</th><th>Photo</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php foreach ($guides as $guide): ?>
                <tr>
                    <td><?php echo $guide['id']; ?></td>
                    <td><?php echo htmlspecialchars($guide['name']); ?></td>
                    <td><?php echo htmlspecialchars($guide['contact']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($guide['experience'])); ?></td>
                    <td>
                        <?php if ($guide['photo']): ?>
                            <img src="../<?php echo $guide['photo']; ?>" class="img-thumb">
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="?delete=<?php echo $guide['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this guide?')">Delete</a>
                        <button class="btn btn-sm btn-warning" onclick='editGuide(<?php echo json_encode($guide); ?>)'>Edit</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function editGuide(guide) {
    document.getElementById('guideId').value = guide.id;
    document.getElementById('name').value = guide.name;
    document.getElementById('contact').value = guide.contact;
    document.getElementById('experience').value = guide.experience;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
</body>
</html>