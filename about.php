<?php include 'includes/nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About Us - TravelEase Mangaluru</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- AOS Animation CSS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!-- Custom Styles -->
  <style>
    body {
      background-color: #fff;
      color: #111;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .tulunad-red { color: #c8102e; }
    .tulunad-bg-red { background-color: #c8102e; }
    .hero {
      background: linear-gradient(rgba(0,0,0,0.8), rgba(200, 16, 46, 0.8)),
        url('assets/images/tulunad-flag.jpg') no-repeat center center;
      background-size: cover;
      color: white;
      padding: 6rem 1rem;
      text-align: center;
    }
    .hero h1 {
      font-size: 3.5rem;
      font-weight: bold;
      animation: fadeInUp 1.5s ease;
    }
    .section-title {
      position: relative;
      font-size: 2rem;
      font-weight: 600;
      margin-bottom: 2rem;
      color: #c8102e;
    }
    .section-title::after {
      content: '';
      width: 60px;
      height: 4px;
      background: #c8102e;
      position: absolute;
      left: 0;
      bottom: -10px;
    }
    .video-container {
      position: relative;
      padding-bottom: 56.25%;
      height: 0;
      overflow: hidden;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.15);
    }
    .video-container iframe {
      position: absolute;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
    }
    .culture-img {
      border-radius: 8px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
      transition: transform 0.4s;
    }
    .culture-img:hover {
      transform: scale(1.03);
    }
  </style>
</head>
<body>

<!-- Hero Section -->
<section class="hero">
  <h1 data-aos="fade-up">About TravelEase Mangaluru</h1>
  <p class="lead" data-aos="fade-up" data-aos-delay="300">Experience culture, connect with roots, and travel smart across Tulunadu.</p>
</section>

<!-- Main Content -->
<div class="container my-5">

  <!-- Our Mission -->
  <section data-aos="fade-up">
    <h2 class="section-title">Our Mission</h2>
    <p>TravelEase Mangaluru is a comprehensive travel-tech platform focused on trip planning, local cultural guidance, and virtual exploration for Mangaluru and the greater Tulunad region. Our goal is to blend technology with tradition — helping tourists discover destinations, book guides, attend events, and immerse in authentic experiences.</p>
  </section>

  <!-- Local Culture -->
  <section class="mt-5" data-aos="fade-up">
    <h2 class="section-title">Tulunad Culture</h2>
    <div class="row align-items-center">
      <div class="col-md-6 mb-3">
        <img src="assets/images/slider2.jpg" alt="Yakshagana" class="img-fluid culture-img">
      </div>
      <div class="col-md-6">
        <p>Yakshagana, Kola, Nema, and Kambala are more than traditions — they're soul of the soil. With elaborate costumes, musical storytelling, and divine rituals, Tulunadu's cultural fabric is unlike any other.</p>
        <p>TravelEase connects you with live performances, 360° views of ritual sites, and schedules of traditional events like <strong>Yakshagana</strong> and <strong>Kambala</strong>.</p>
      </div>
    </div>
  </section>

  <!-- Video Embeds -->
  <section class="mt-5" data-aos="fade-up">
    <h2 class="section-title">Cultural Highlights</h2>
    <div class="row text-center">
      <div class="col-md-6 mb-4">
        <div class="video-container">
          <iframe src="https://www.youtube.com/embed/qnT9peO33xQ" frameborder="0" allowfullscreen></iframe>
        </div>
        <p class="mt-2">A Yakshagana performance from rural Tulunadu.</p>
      </div>
      <div class="col-md-6 mb-4">
        <div class="video-container">
          <iframe src="https://www.youtube.com/embed/PB8xkWSWi1Q" frameborder="0" allowfullscreen></iframe>
        </div>
        <p class="mt-2">Kambala – The iconic buffalo race on slushy fields.</p>
      </div>
    </div>
  </section>

  <!-- Why Visit -->
  <section class="mt-5" data-aos="fade-up">
    <h2 class="section-title">Why Visit Mangaluru?</h2>
    <p>Mangaluru is where the coast meets culture. From serene beaches like Panambur and Tannirbhavi to temples like Kudroli Gokarnanatheshwara and Kadri Manjunath — the city offers a complete spiritual and sensory experience. Add spicy Mangalorean cuisine and friendly locals, and your trip becomes unforgettable.</p>
  </section>

</div>

<?php include 'includes/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- GSAP + AOS for Animation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({ duration: 1200 });
</script>
</body>
</html>
