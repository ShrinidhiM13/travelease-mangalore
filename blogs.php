<?php include 'includes/nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blogs - TravelEase Mangaluru</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- AOS Animation CSS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff;
    }
    .section-header {
      background-color: #c8102e;
      color: white;
      padding: 4rem 1rem;
      text-align: center;
    }
    .section-header h1 {
      font-size: 3rem;
      font-weight: bold;
    }
    .blog-card {
      border: none;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      transition: transform 0.3s;
      border-radius: 10px;
    }
    .blog-card:hover {
      transform: translateY(-5px);
    }
    .blog-image {
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
      height: 200px;
      object-fit: cover;
    }
    .blog-body {
      padding: 1rem;
    }
    .read-more {
      color: #c8102e;
      text-decoration: none;
      font-weight: 500;
    }
    .read-more:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<section class="section-header">
  <h1 data-aos="fade-down">Travel Blogs</h1>
  <p class="lead" data-aos="fade-up" data-aos-delay="300">
    Explore stories, tips, and cultural highlights from Mangaluru and Tulunadu.
  </p>
</section>

<div class="container my-5">
  <div class="row">
    <?php
      require_once 'config/db.php'; // This should return a $pdo instance

      try {
          $stmt = $pdo->query("SELECT * FROM blogs ORDER BY created_at DESC");
          $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

          if (count($blogs) > 0) {
              foreach ($blogs as $row) {
    ?>
    <div class="col-md-4 mb-4 text-center" data-aos="fade-up">
      <div class="card blog-card">
        <img src="<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top blog-image" alt="Blog Image">
        <div class="card-body blog-body">
          <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
          <p class="card-text">
            <?php echo substr(strip_tags($row['content']), 0, 100) . '...'; ?>
          </p>
          <a href="blog-details.php?id=<?php echo $row['id']; ?>" class="read-more">Read More</a>
        </div>
      </div>
    </div>
    <?php
              }
          } else {
              echo '<div class="col-12 text-center"><p>No blog posts found.</p></div>';
          }
      } catch (PDOException $e) {
          echo '<div class="col-12 text-center text-danger"><p>Error fetching blogs.</p></div>';
      }
    ?>
  </div>
</div>

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
