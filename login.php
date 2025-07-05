<?php include 'includes/nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login & Register - TravelEase Mangaluru</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #c8102e, #000);
      color: white;
      font-family: 'Segoe UI', sans-serif;
    }
    .auth-container {
      max-width: 900px;
      margin: 50px auto;
      background: #fff;
      border-radius: 12px;
      padding: 30px;
      color: #000;
      box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }
    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(200, 16, 46, 0.25);
    }
    .btn-danger {
      background-color: #c8102e;
      border: none;
    }
    .btn-danger:hover {
      background-color: #a00d24;
    }
  </style>
</head>
<body>

<div class="container auth-container" data-aos="fade-up">
  <div class="row">
    <!-- Login Form -->
    <div class="col-md-6 border-end">
      <h3 class="text-center mb-4">Login</h3>
      <form action="actions/login.php" method="POST">
        <div class="mb-3">
          <label for="login_email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="login_email" name="email" required>
        </div>
        <div class="mb-3">
          <label for="login_password" class="form-label">Password</label>
          <input type="password" class="form-control" id="login_password" name="password" required>
        </div>
        <button type="submit" class="btn btn-danger w-100">Login</button>
      </form>
    </div>

    <!-- Registration Form -->
    <div class="col-md-6">
      <h3 class="text-center mb-4">Register</h3>
      <form action="actions/register.php" method="POST">
        <div class="mb-3">
          <label for="reg_name" class="form-label">Full Name</label>
          <input type="text" class="form-control" id="reg_name" name="name" required>
        </div>
        <div class="mb-3">
          <label for="reg_email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="reg_email" name="email" required>
        </div>
        <div class="mb-3">
          <label for="reg_password" class="form-label">Password</label>
          <input type="password" class="form-control" id="reg_password" name="password" required>
        </div>
        <button type="submit" class="btn btn-danger w-100">Register</button>
      </form>
    </div>
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