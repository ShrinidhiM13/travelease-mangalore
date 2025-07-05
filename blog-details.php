<?php include 'includes/nav.php'; ?>
<?php
require_once 'config/db.php';

$blog = null;

// Validate ID from URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching blog: " . $e->getMessage());
    }
} else {
    die("Invalid blog ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $blog ? htmlspecialchars($blog['title']) : 'Blog Not Found'; ?> - TravelEase Mangaluru</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- AOS CSS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff;
    }
    .blog-header {
      background-color: #c8102e;
      color: white;
      padding: 4rem 1rem;
      text-align: center;
    }
    .blog-header h1 {
      font-size: 3rem;
      font-weight: bold;
    }
    .blog-content {
      padding: 2rem 1rem;
    }
    .blog-image {
      width: 100%;
      max-height: 400px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 2rem;
    }
  </style>
      
</head>
<body>

<?php if ($blog): ?>
  <section class="blog-header" data-aos="fade-down">
    <h1><?php echo htmlspecialchars($blog['title']); ?></h1>
    <p class="lead" data-aos="fade-up" data-aos-delay="300">
      Posted on <?php echo date("F j, Y", strtotime($blog['created_at'])); ?>
    </p>
  </section>

  <div class="container blog-content" data-aos="fade-up">
    <img src="<?php echo htmlspecialchars($blog['image']); ?>" alt="Blog Image" class="blog-image">
    <div class="blog-text">
      <?php echo $blog['content']; // Safe if you're using a WYSIWYG editor like TinyMCE ?>
    </div>
  </div>
<?php else: ?>
  <div class="container text-center py-5">
    <h2 class="text-danger">Blog not found.</h2>
    <a href="blogs.php" class="btn btn-outline-danger mt-3">← Back to Blogs</a>
  </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();
</script>
</body>
</html>
