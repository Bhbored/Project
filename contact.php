<?php
// contact.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | Brewtopia</title>
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
                        <a class="nav-link" href="about_us.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="contact.php">Contact</a>
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
        <div class="row">
            <div class="col-md-6 mb-4 mb-md-0">
                <h1 class="mb-3" style="color:#4e342e;">Contact Us</h1>
                <p class="lead">We'd love to hear from you! Whether you have a question, feedback, or just want to say hello, reach out to us using the form or info below.</p>
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Your Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="you@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" rows="4" placeholder="How can we help you?" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
            <div class="col-md-6">
                <div class="bg-light rounded shadow p-4 h-100">
                    <h4 class="mb-3" style="color:#4e342e;">Our Coffee Shop</h4>
                    <p><strong>Address:</strong> 123 Brew Lane, Coffee City, 12345</p>
                    <p><strong>Phone:</strong> (123) 456-7890</p>
                    <p><strong>Email:</strong> hello@brewtopia.com</p>
                    <hr>
                    <h5 class="mb-2">Find Us Here:</h5>
                    <div style="width:100%;height:200px;background:#e0c9a6;border-radius:0.5rem;display:flex;align-items:center;justify-content:center;color:#4e342e;font-weight:bold;">
                        [Google Maps Placeholder]
                    </div>
                </div>
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
