<?php include 'includes/nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Destinations - TravelEase Mangaluru</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff;
    }
    .header-section {
      background-color: #c8102e;
      color: white;
      padding: 4rem 1rem;
      text-align: center;
    }
    .header-section h1 {
      font-size: 3rem;
    }
    .destination-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: 0.3s;
    }
    .destination-card:hover {
      transform: translateY(-5px);
    }
    .destination-img {
      height: 200px;
      object-fit: cover;
    }
    .iframe-wrapper iframe {
      width: 100%;
      height: 200px;
      border: none;
      border-radius: 8px;
    }
  </style>
</head>
<body>

<section class="header-section">
  <h1 data-aos="zoom-in">Popular Destinations in Mangaluru</h1>
  <p class="lead" data-aos="fade-up" data-aos-delay="200">Experience the scenic, spiritual, and cultural charm of Tulunadu</p>
</section>

<div class="container my-5">
  <div class="row">
    <?php
      include 'config/db.php';

      // Prepare and execute the query
      $stmt = $pdo->query("SELECT * FROM destinations ORDER BY id DESC");
      $destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if (count($destinations) > 0) {
        foreach ($destinations as $row) {
    ?>
<div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up">
  <div class="card destination-card">
    <img src="<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top destination-img" alt="<?php echo htmlspecialchars($row['name']); ?>">
    <div class="card-body">
      <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
      <p class="card-text"><?php echo htmlspecialchars(substr($row['description'], 0, 120)); ?>...</p>
      <div class="iframe-wrapper">
        <?php echo $row['gmap_embed']; // Assuming this is trusted embed HTML ?>
      </div>
      <div class="text-center mt-3">
        <a href="packages.php" class="btn btn-primary">Book Now</a>
      </div>
    </div>
  </div>
</div>


    <?php
        }
      } else {
    ?>
    <div class="col-12 text-center">
      <p>No destinations available yet.</p>
    </div>
    <?php } ?>
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
