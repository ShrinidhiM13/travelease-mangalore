<?php
session_start();
require_once '../config/db.php';

// Redirect non-admins
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle Add/Edit/Delete

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add or Edit
    $id = $_POST['id'] ?? null;
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    
    // Validate
    if ($title === '') {
        $errors[] = "Title is required.";
    }
    if ($content === '') {
        $errors[] = "Content is required.";
    }

    // Handle image upload if any
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['image']['type'], $allowed)) {
            $errors[] = "Only JPG, PNG, GIF images allowed.";
        } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
            $errors[] = "Image size must be under 2MB.";
        } else {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('blog_', true) . "." . $ext;
            $destination = "../uploads/blogs/" . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $imagePath = "uploads/blogs/" . $filename;
            } else {
                $errors[] = "Failed to upload image.";
            }
        }
    }

    if (!$errors) {
        if ($id) {
            // Update existing blog
            if ($imagePath) {
                $stmt = $pdo->prepare("UPDATE blogs SET title = ?, content = ?, image = ? WHERE id = ?");
                $stmt->execute([$title, $content, $imagePath, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE blogs SET title = ?, content = ? WHERE id = ?");
                $stmt->execute([$title, $content, $id]);
            }
            $success = "Blog updated successfully.";
        } else {
            // Insert new blog
            $stmt = $pdo->prepare("INSERT INTO blogs (title, content, image) VALUES (?, ?, ?)");
            $stmt->execute([$title, $content, $imagePath]);
            $success = "New blog added successfully.";
        }
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    // Optionally delete image file here
    $stmt = $pdo->prepare("DELETE FROM blogs WHERE id = ?");
    $stmt->execute([$deleteId]);
    header("Location: manage-blogs.php");
    exit;
}

// Fetch all blogs
$stmt = $pdo->query("SELECT * FROM blogs ORDER BY created_at DESC");
$blogs = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Manage Blogs - Admin - TravelEase Mangaluru</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
  .blog-image-thumb {
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
</style>
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container my-4">
  <h1 class="mb-4">Manage Blogs</h1>

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
    <h3>Add / Edit Blog</h3>
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" id="blogId" />
      <div class="mb-3">
        <label for="title" class="form-label">Title *</label>
        <input type="text" name="title" id="title" class="form-control" required />
      </div>
      <div class="mb-3">
        <label for="content" class="form-label">Content *</label>
        <textarea name="content" id="content" rows="5" class="form-control" required></textarea>
      </div>
      <div class="mb-3">
        <label for="image" class="form-label">Image (optional)</label>
        <input type="file" name="image" id="image" class="form-control" accept="image/*" />
      </div>
      <button type="submit" class="btn btn-danger">Save Blog</button>
      <button type="button" class="btn btn-secondary" id="resetForm">Clear</button>
    </form>
  </div>

  <h3>Existing Blogs</h3>
  <table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Image</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($blogs): ?>
        <?php foreach ($blogs as $blog): ?>
          <tr>
            <td><?php echo $blog['id']; ?></td>
            <td><?php echo htmlspecialchars($blog['title']); ?></td>
            <td>
              <?php if ($blog['image']): ?>
                <img src="../<?php echo htmlspecialchars($blog['image']); ?>" alt="Blog Image" class="blog-image-thumb" />
              <?php else: ?>
                No Image
              <?php endif; ?>
            </td>
            <td><?php echo date('d M Y, H:i', strtotime($blog['created_at'])); ?></td>
            <td>
              <button class="btn btn-sm btn-primary edit-btn" 
                data-id="<?php echo $blog['id']; ?>"
                data-title="<?php echo htmlspecialchars($blog['title'], ENT_QUOTES); ?>"
                data-content="<?php echo htmlspecialchars($blog['content'], ENT_QUOTES); ?>"
                >Edit</button>
              <a href="?delete=<?php echo $blog['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this blog?');">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="5" class="text-center">No blogs found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<script>
// Fill form with blog data on Edit button click
document.querySelectorAll('.edit-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.getAttribute('data-id');
    const title = btn.getAttribute('data-title');
    const content = btn.getAttribute('data-content');

    document.getElementById('blogId').value = id;
    document.getElementById('title').value = title;
    document.getElementById('content').value = content;
    // Scroll to form
    window.scrollTo({top: 0, behavior: 'smooth'});
  });
});

// Clear form button
document.getElementById('resetForm').addEventListener('click', () => {
  document.getElementById('blogId').value = '';
  document.getElementById('title').value = '';
  document.getElementById('content').value = '';
  document.getElementById('image').value = '';
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
