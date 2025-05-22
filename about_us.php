<?php
// about_us.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Brewtopia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
</head>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-gradient-custom shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Brewtopia</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="about_us.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
                <button class="btn btn-outline-light ms-lg-3 mt-2 mt-lg-0" data-bs-toggle="modal" data-bs-target="#loginModal">
                    Login
                </button>
            </div>
        </div>
    </nav>
</header>

<body>
    <section class="container my-5">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <img src="images/cover.jpeg" alt="About Brewtopia" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-6">
                <h1 class="mb-3" style="color:#4e342e;">Our Story</h1>
                <p class="lead">Brewtopia was founded with a simple mission: to create the perfect coffee experience for everyone. From ethically sourced beans to expertly crafted drinks, we believe every cup should be a moment of joy.</p>
                <p>Our journey began in 2020, when a group of coffee lovers decided to bring specialty coffee to the heart of the community. Today, Brewtopia is more than just a coffee shop—it's a place to connect, relax, and savor the best brews in town.</p>
                <ul>
                    <li><strong>Ethically Sourced Beans</strong></li>
                    <li><strong>Expert Baristas</strong></li>
                    <li><strong>Warm, Inviting Atmosphere</strong></li>
                    <li><strong>Community Events & Workshops</strong></li>
                </ul>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 text-center">
                <h2 class="mb-4" style="color:#4e342e;">Meet Our Team</h2>
            </div>
            <div class="col-md-4 text-center">
                <img src="images/Emma.jpeg" alt="Emma - Head Barista" class="rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover;">
                <h5>Emma</h5>
                <p class="text-muted">Head Barista</p>
                <p>Passionate about latte art and always ready with a smile.</p>
            </div>
            <div class="col-md-4 text-center">
                <img src="images/Liam.jpeg" alt="Liam - Coffee Roaster" class="rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover;">
                <h5>Liam</h5>
                <p class="text-muted">Coffee Roaster</p>
                <p>Ensures every bean is roasted to perfection for the freshest flavor.</p>
            </div>
            <div class="col-md-4 text-center">
                <img src="images/Sophia.jpeg" alt="Sophia - Manager" class="rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover;">
                <h5>Sophia</h5>
                <p class="text-muted">Manager</p>
                <p>Dedicated to making Brewtopia a welcoming space for all.</p>
            </div>
        </div>
    </section>
    <footer class="bg-dark text-light py-4 text-center">
        <p>&copy; 2025 Brewtopia. All rights reserved.</p>
    </footer>
    <?php include 'flexible components/LoginModel.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>