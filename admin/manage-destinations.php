<?php
session_start();
require_once '../config/db.php';

// Redirect if not admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $gmap_embed = trim($_POST['gmap_embed'] ?? '');

    // Validation
    if ($name === '') $errors[] = "Name is required.";

    // Image upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['image']['type'], $allowed)) {
            $errors[] = "Only JPG, PNG, GIF images allowed.";
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            $errors[] = "Image size must be under 2MB.";
        } else {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('dest_', true) . "." . $ext;
            $destination = "../uploads/destinations/" . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $imagePath = "uploads/destinations/" . $filename;
            } else {
                $errors[] = "Failed to upload image.";
            }
        }
    }

    if (!$errors) {
        if ($id) {
            // Update
            if ($imagePath) {
                $stmt = $pdo->prepare("UPDATE destinations SET name=?, description=?, image=?, gmap_embed=? WHERE id=?");
                $stmt->execute([$name, $description, $imagePath, $gmap_embed, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE destinations SET name=?, description=?, gmap_embed=? WHERE id=?");
                $stmt->execute([$name, $description, $gmap_embed, $id]);
            }
            $success = "Destination updated successfully.";
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO destinations (name, description, image, gmap_embed) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $description, $imagePath, $gmap_embed]);
            $success = "New destination added successfully.";
        }
    }
}

// Delete
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    // Optional: delete image file here if you want
    $stmt = $pdo->prepare("DELETE FROM destinations WHERE id=?");
    $stmt->execute([$deleteId]);
    header("Location: manage-destinations.php");
    exit;
}

// Fetch all destinations
$stmt = $pdo->query("SELECT * FROM destinations ORDER BY id DESC");
$destinations = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Manage Destinations - Admin - TravelEase Mangaluru</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
  .dest-image-thumb {
    max-width: 100px;
    max-height: 80px;
    object-fit: cover;
  }
  .form-section {
    background: #f8f9fa;
    padding: 20px;
    margin-bottom: 30px;
    border-radius: 8px;
  }
  iframe.gmap-preview {
    width: 250px;
    height: 150px;
    border: none;
  }
</style>
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container my-4">
  <h1 class="mb-4">Manage Destinations</h1>

  <?php if ($success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>

  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <ul>
        <?php foreach ($errors as $e) {
          echo '<li>' . htmlspecialchars($e) . '</li>';
        } ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="form-section">
    <h3>Add / Edit Destination</h3>
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" id="destId" />
      <div class="mb-3">
        <label for="name" class="form-label">Name *</label>
        <input type="text" name="name" id="name" class="form-control" required />
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" rows="3" class="form-control"></textarea>
      </div>
      <div class="mb-3">
        <label for="image" class="form-label">Image (optional)</label>
        <input type="file" name="image" id="image" class="form-control" accept="image/*" />
      </div>
      <div class="mb-3">
        <label for="gmap_embed" class="form-label">Google Maps 360 View Embed Code (iframe)</label>
        <textarea name="gmap_embed" id="gmap_embed" rows="4" class="form-control" placeholder='Paste full iframe embed code here'></textarea>
      </div>
      <button type="submit" class="btn btn-danger">Save Destination</button>
      <button type="button" class="btn btn-secondary" id="resetForm">Clear</button>
    </form>
  </div>

  <h3>Existing Destinations</h3>
  <table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Image</th>
        <th>360 View</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($destinations): ?>
        <?php foreach ($destinations as $dest): ?>
          <tr>
            <td><?php echo $dest['id']; ?></td>
            <td><?php echo htmlspecialchars($dest['name']); ?></td>
            <td>
              <?php if ($dest['image']): ?>
                <img src="../<?php echo htmlspecialchars($dest['image']); ?>" alt="Destination Image" class="dest-image-thumb" />
              <?php else: ?>
                No Image
              <?php endif; ?>
            </td>
            <td>
              <?php
                if ($dest['gmap_embed']) {
                  echo $dest['gmap_embed'];
                } else {
                  echo "N/A";
                }
              ?>
            </td>
            <td>
              <button class="btn btn-sm btn-primary edit-btn" 
                data-id="<?php echo $dest['id']; ?>"
                data-name="<?php echo htmlspecialchars($dest['name'], ENT_QUOTES); ?>"
                data-description="<?php echo htmlspecialchars($dest['description'], ENT_QUOTES); ?>"
                data-gmap_embed="<?php echo htmlspecialchars($dest['gmap_embed'], ENT_QUOTES); ?>"
              >Edit</button>
              <a href="?delete=<?php echo $dest['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this destination?');">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="5" class="text-center">No destinations found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<script>
document.querySelectorAll('.edit-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.getAttribute('data-id');
    const name = btn.getAttribute('data-name');
    const description = btn.getAttribute('data-description');
    const gmap_embed = btn.getAttribute('data-gmap_embed');

    document.getElementById('destId').value = id;
    document.getElementById('name').value = name;
    document.getElementById('description').value = description;
    document.getElementById('gmap_embed').value = gmap_embed;

    window.scrollTo({top: 0, behavior: 'smooth'});
  });
});

document.getElementById('resetForm').addEventListener('click', () => {
  document.getElementById('destId').value = '';
  document.getElementById('name').value = '';
  document.getElementById('description').value = '';
  document.getElementById('gmap_embed').value = '';
  document.getElementById('image').value = '';
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
