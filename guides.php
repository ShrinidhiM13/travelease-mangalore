<?php include 'includes/nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Local Guides - TravelEase Mangaluru</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }
    .guide-section {
      background: linear-gradient(to right, #c8102e, #000);
      color: white;
      padding: 60px 0;
      text-align: center;
    }
    .guide-card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: transform 0.3s;
    }
    .guide-card:hover {
      transform: scale(1.03);
    }
    .guide-img {
      width: 100%;
      height: 280px;
      object-fit: cover;
    }
    .card-title {
      color: #c8102e;
    }
    .guide-card {
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0,0,0,0.1);
  overflow: hidden;
}

.guide-img {
  width: 100%;
  height: 250px;
  object-fit: cover;
}

  </style>
</head>
<body>

<section class="guide-section">
  <div class="container">
    <h1 data-aos="fade-down">Meet Our Local Guides</h1>
    <p class="lead" data-aos="fade-up" data-aos-delay="200">
      Explore Mangaluru with help from friendly and experienced locals
    </p>
  </div>
</section>

<div class="container py-5">
  <div class="row text-center">
    <?php
      include 'config/db.php'; // provides $pdo (PDO connection)

      // Fetch guides using PDO
      $stmt = $pdo->query("SELECT * FROM guides ORDER BY id DESC");
      $guides = $stmt->fetchAll();

      if (count($guides) > 0):
        foreach ($guides as $row):
    ?>
    <div class="col-md-4 mb-4" data-aos="fade-up">
      <div class="card guide-card">
        <img src="<?php echo htmlspecialchars($row['photo']); ?>" class="guide-img" alt="<?php echo htmlspecialchars($row['name']); ?>" />
        <div class="card-body">
          <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
          <p><strong>Contact:</strong> <?php echo htmlspecialchars($row['contact']); ?></p>
          <p><?php echo nl2br(htmlspecialchars($row['experience'])); ?></p>
          <div class="text-center mt-3">
            <a href="tel:<?php echo htmlspecialchars($row['contact']); ?>" class="btn btn-success">
              Contact Me
            </a>
          </div>
        </div>
      </div>
    </div>
    <?php
        endforeach;
      else:
    ?>
    <div class="col-12 text-center">
      <p>No guides available at the moment. Please check back later.</p>
    </div>
    <?php endif; ?>
  </div>
</div>


<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();
</script>
</body>
</html>
