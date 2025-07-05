<?php include 'includes/nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upcoming Events - TravelEase Mangaluru</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #fff;
    }
    .event-section {
      background-color: #c8102e;
      color: white;
      padding: 4rem 1rem;
      text-align: center;
    }
    .event-section h1 {
      font-size: 3rem;
    }
    .event-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: 0.3s;
    }
    .event-card:hover {
      transform: translateY(-5px);
    }
    .slider {
      display: flex;
      overflow-x: auto;
      scroll-snap-type: x mandatory;
      gap: 10px;
    }
    .slider img {
      scroll-snap-align: start;
      width: 100%;
      border-radius: 8px;
      object-fit: cover;
    }
  </style>
</head>
<body>

<section class="event-section">
  <h1 data-aos="zoom-in">Cultural & Traditional Events</h1>
  <p class="lead" data-aos="fade-up" data-aos-delay="200">Yakshagana, Kambala, Bhoota Kola and much more</p>
</section>

<div class="container text-center my-5">
  <div class="row">
    <?php
      require 'config/db.php';

      $stmt = $pdo->prepare("SELECT * FROM events ORDER BY event_date DESC");
      $stmt->execute();
      $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if ($events):
        foreach ($events as $row):
    ?>
    <div class="col-md-6 mb-4" data-aos="fade-up">
      <div class="card event-card">
        <div class="card-body">
          <h4 class="card-title text-danger"><?php echo htmlspecialchars($row['title']); ?></h4>
          <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($row['event_date'])); ?></p>
          <p class="card-text"><?php echo htmlspecialchars(substr($row['description'], 0, 200)); ?>...</p>
          <div class="slider">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <?php if (!empty($row['image' . $i])): ?>
                <img src="uploads/events/<?php echo htmlspecialchars($row['image' . $i]); ?>" alt="Event Image" class="img-fluid">
              <?php endif; ?>
            <?php endfor; ?>
          </div>
          <?php if (!empty($row['youtube_url'])): ?>
<div class="mt-3 text-center">
  <a href="<?php echo htmlspecialchars($row['youtube_url']); ?>" target="_blank" class="btn btn-outline-danger">
    Watch on YouTube
  </a>
</div>

          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php
        endforeach;
      else:
    ?>
    <div class="col-12 text-center">
      <p>No upcoming events yet. Stay tuned!</p>
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
