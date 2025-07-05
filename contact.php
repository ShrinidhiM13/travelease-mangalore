<?php include 'includes/nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - TravelEase Mangaluru</title>

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
    .contact-header {
      background-color: #c8102e;
      color: white;
      padding: 3rem 1rem;
      text-align: center;
    }
    .contact-header h1 {
      font-size: 2.8rem;
    }
    .contact-info-box {
      background: #f8f9fa;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .form-section {
      padding: 2rem;
    }
    .btn-danger {
      background-color: #c8102e;
      border: none;
    }
  </style>
</head>
<body>

<section class="contact-header" data-aos="fade-down">
  <h1>Contact Us</h1>
  <p class="lead">We’d love to hear from you! Fill out the form or reach us directly.</p>
</section>

<div class="container my-5">
  <div class="row g-4">
    <div class="col-md-6" data-aos="fade-right">
      <div class="contact-info-box">
        <h4>Address</h4>
        <p>TravelEase Mangaluru, Hampankatta, Mangaluru, Karnataka - 575001</p>

        <h4>Phone</h4>
        <p>+91 98765 43210</p>

        <h4>Email</h4>
        <p>support@traveleasemangalore.com</p>

        <h4>Follow Us</h4>
        <p>
          <a href="#" target="_blank">Facebook</a> |
          <a href="#" target="_blank">Instagram</a> |
          <a href="#" target="_blank">Twitter</a>
        </p>

        <h4>Google Map</h4>
        <iframe src="https://maps.google.com/maps?q=Mangalore&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
      </div>
    </div>

    <div class="col-md-6" data-aos="fade-left">
      <div class="form-section">
        <form method="POST" action="contact-submit.php">
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
          </div>
          <button type="submit" class="btn btn-danger">Send Message</button>
        </form>
      </div>
    </div>
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