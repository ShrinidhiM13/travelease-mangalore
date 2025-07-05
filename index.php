<?php
session_start();
require_once 'config/db.php'; // Make sure this path is correct

$feedbackSuccess = false;

// Feedback form handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'], $_POST['comment'])) {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['feedback_errors'] = ["You must be logged in to submit feedback."];
        header("Location: login.php");
        exit;
    }

    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    if ($rating < 1 || $rating > 5 || empty($comment)) {
        $_SESSION['feedback_errors'] = ["Please provide a valid rating and comment."];
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO feedback (user_id, rating, comment, submitted_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$user_id, $rating, $comment]);
            $feedbackSuccess = true;
        } catch (PDOException $e) {
            $_SESSION['feedback_errors'] = ["Error saving feedback. Please try again."];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TravelEase Mangaluru</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }
        header, footer {
            background: #000;
            color: white;
        }
        .tulunad-theme {
            background: linear-gradient(90deg, #d90429, #000, #ffffff);
            color: white;
        }
        .hero-slider {
            height: 90vh;
            position: relative;
            overflow: hidden;
        }
        .hero-slider img {
            width: 100%;
            height: 90vh;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            transition: opacity 2s ease-in-out;
            opacity: 0;
        }
        .hero-slider img.active {
            opacity: 1;
        }
        .search-overlay {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            text-align: center;
        }
        .search-overlay input {
            width: 300px;
            max-width: 90%;
        }
        .feedback-stars i {
            cursor: pointer;
            font-size: 1.5rem;
        }
        .app-links a {
            margin: 0 10px;
            font-weight: bold;
        }
        .search-overlay {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
            padding: 40px 20px;
            border-radius: 12px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .search-overlay h2 {
            margin-bottom: 20px;
            font-weight: bold;
            color: #fff;
        }

        .search-overlay input.form-control {
            max-width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
        }

        .search-overlay .btn {
            padding: 10px 20px;
            font-weight: 600;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<?php include 'includes/nav.php'; ?>

<header class="py-3 text-center">
    <h1>TravelEase Mangaluru</h1>
    <p>Explore the Culture, Places & Heritage of Tulu Nadu</p>
</header>

<section class="hero-slider position-relative">
    <img src="assets/images/slider1.jpg" class="active" alt="Mangalore 1">
    <img src="assets/images/slider2.jpg" alt="Mangalore 2">
    <img src="assets/images/slider3.jpg" alt="Mangalore 3">

    <div class="hero-section text-center d-flex align-items-center justify-content-center" style="height: 100vh; background: url('assets/images/tulunad-flag.jpg') center/cover no-repeat;">
  <div class="overlay" style="background-color: rgba(0, 0, 0, 0.6); position: absolute; inset: 0;"></div>
  <div class="container position-relative text-white" data-aos="fade-up">
    <h1 class="display-3 fw-bold">Welcome to TravelEase Mangaluru</h1>
    <p class="lead mt-3">Discover the hidden gems, vibrant culture, and breathtaking destinations of Mangaluru & Tulunadu.</p>
    <div class="mt-4 d-flex justify-content-center gap-3 flex-wrap">
      <a href="destinations.php" class="btn btn-outline-light btn-lg px-4">Explore Destinations</a>
      <a href="packages.php" class="btn btn-danger btn-lg px-4">View Packages</a>
    </div>
  </div>
</div>

</section>

<section class="container py-5">
    <div class="row text-center">
        <div class="col-md-6 mb-4">
            <h4>Nearest Railway Station</h4>
            <p>Mangalore Central (MAQ)</p>
            <div class="ratio ratio-16x9">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.560139651301!2d74.8436370758752!3d12.86979228743695!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba35bbd82d5353f%3A0x6b31b0ed54c931cb!2sMangaluru%20Central%20Railway%20Station!5e0!3m2!1sen!2sin!4v1716279582109!5m2!1sen!2sin" 
                    width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <h4>Nearest Airport</h4>
            <p>Mangalore International Airport (IXE)</p>
            <div class="ratio ratio-16x9">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.3943864470524!2d74.88331627587458!3d12.96374508736343!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba3528e5eb98a5d%3A0x30b4c75e699d65cf!2sMangalore%20International%20Airport!5e0!3m2!1sen!2sin!4v1716279780744!5m2!1sen!2sin" 
                    width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

       <section class="travel-apps-section py-5 bg-light">
  <div class="container text-center">
    <h3 class="mb-4 fw-bold" style="color: #c8102e;">Travel Apps</h3>
    <div class="d-flex justify-content-center gap-5 flex-wrap">
      <a href="https://www.olacabs.com" target="_blank" class="text-decoration-none text-center">
        <img src="assets/images/ola.png" alt="Ola" width="70" height="35" class="mb-2">
        <div class="fw-semibold" style="color: #333;">Ola</div>
      </a>

      <a href="https://www.uber.com" target="_blank" class="text-decoration-none text-center">
        <img src="assets/images/uber.png" alt="Uber" width="70" height="35" class="mb-2">
        <div class="fw-semibold" style="color: #333;">Uber</div>
      </a>

      <a href="https://www.rapido.bike" target="_blank" class="text-decoration-none text-center">
        <img src="assets/images/rapido.webp" alt="Rapido" width="70" height="35" class="mb-2">
        <div class="fw-semibold" style="color: #333;">Rapido</div>
      </a>
    </div>
  </div>
</section>

    </div>
</section>

<section class="bg-light py-5">
    <div class="container text-center">
        <h3>Currency Converter</h3>
        <div class="row justify-content-center">
            <div class="col-md-3">
                <input type="number" id="amount" class="form-control" placeholder="Amount in INR">
            </div>
            <div class="col-md-3">
                <select id="currency" class="form-select">
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="AED">AED</option>
                    <option value="JPY">JPY</option>
                </select>
            </div>
            <div class="col-md-2">
                <button onclick="convertCurrency()" class="btn btn-dark">Convert</button>
            </div>
        </div>
        <p class="mt-3" id="converted"></p>
    </div>
</section>

<!-- Feedback Section -->
<section class="container py-5">
    <h3 class="text-center">Leave Feedback</h3>

    <?php if ($feedbackSuccess): ?>
        <div class="alert alert-success text-center">Thank you! Your feedback has been submitted.</div>
    <?php elseif (!empty($_SESSION['feedback_errors'])): ?>
        <div class="alert alert-danger text-center">
            <?php 
                foreach ($_SESSION['feedback_errors'] as $error) {
                    echo htmlspecialchars($error) . "<br>";
                }
                unset($_SESSION['feedback_errors']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (!$feedbackSuccess): ?>
    <form method="post" action="" class="text-center">
        <div class="feedback-stars mb-3">
            <input type="hidden" name="rating" id="rating" value="0">
            <i class="fa fa-star" onclick="rate(1)"></i>
            <i class="fa fa-star" onclick="rate(2)"></i>
            <i class="fa fa-star" onclick="rate(3)"></i>
            <i class="fa fa-star" onclick="rate(4)"></i>
            <i class="fa fa-star" onclick="rate(5)"></i>
        </div>
        <textarea name="comment" class="form-control mb-3" rows="3" placeholder="Your feedback"></textarea>
        <button class="btn btn-danger">Submit</button>
    </form>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>

<!-- JS -->
<script>
    const slides = document.querySelectorAll(".hero-slider img");
    let current = 0;
    setInterval(() => {
        slides[current].classList.remove("active");
        current = (current + 1) % slides.length;
        slides[current].classList.add("active");
    }, 5000);

    function rate(n) {
        const stars = document.querySelectorAll(".feedback-stars i");
        stars.forEach((star, index) => {
            star.classList.toggle("text-warning", index < n);
        });
        document.getElementById("rating").value = n;
    }

    async function convertCurrency() {
        const amount = document.getElementById("amount").value;
        const to = document.getElementById("currency").value;

        if (!amount) return;

        try {
            const res = await fetch(`https://api.exchangerate-api.com/v4/latest/INR`);
            const data = await res.json();
            const converted = amount * data.rates[to];
            document.getElementById("converted").innerText = `Converted: ${converted.toFixed(2)} ${to}`;
        } catch (error) {
            document.getElementById("converted").innerText = 'Failed to convert.';
        }
    }
</script>

</body>
</html>
