<?php
session_start();

// Simple login handling:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $isAdmin = isset($_POST['admin']);

    if ($isAdmin) {
        if ($email === 'admin' && $password === 'admin') {
            $_SESSION['role'] = 'admin';
            header('Location: admin.php');
            exit;
        } else {
            $error = 'Invalid admin credentials.';
        }
    } else {
        // any email/password accepted for user except 'admin' login
        if (strtolower($email) === 'admin') {
            $error = 'Invalid user credentials.';
        } else {
            $_SESSION['role'] = 'user';
            $_SESSION['email'] = $email;
            header('Location: user.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Brewtopia - Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
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
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#menu">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
                <button class="btn btn-outline-light ms-lg-3 mt-2 mt-lg-0" data-bs-toggle="modal" data-bs-target="#loginModal">
                    Login
                </button>
            </div>
        </div>
    </nav>
    <header class="hero bg-image text-white d-flex align-items-center justify-content-center" style="background-image: url('images/background.jpeg'); height: 70vh; margin-top: 0;">
        <div class="text-center p-5 bg-black bg-opacity-50 rounded">
            <h1>Welcome to Brewtopia</h1>
            <p>Your perfect coffee experience starts here.</p>
            <a href="#menu" class="btn btn-warning btn-lg">Explore Menu</a>
        </div>
    </header>

    <main class="container my-5" id="menu">
        <h2 class="mb-4">Our Coffee Selection</h2>
        <div class="row">
            <!-- Placeholder cards -->
            <?php
            $coffeeItems = [
                [
                    "name" => "Espresso",
                    "desc" => "A strong and bold coffee shot.",
                    "price" => 2.50,
                    "img" => "images/espresso.jpg"
                ],
                [
                    "name" => "Cappuccino",
                    "desc" => "Espresso with steamed milk and foam.",
                    "price" => 3.00,
                    "img" => "images/Cappuccino.jpg"
                ],
                [
                    "name" => "Latte",
                    "desc" => "Creamy blend of espresso and milk.",
                    "price" => 3.50,
                    "img" => "images/Latte.jpg"
                ],
                [
                    "name" => "Mocha",
                    "desc" => "Chocolate flavored coffee delight.",
                    "price" => 3.75,
                    "img" => "images/Mocha.jpg"
                ],
                [
                    "name" => "Americano",
                    "desc" => "Espresso diluted with hot water.",
                    "price" => 2.75,
                    "img" => "images/Americano.jpg"
                ],
                [
                    "name" => "Macchiato",
                    "desc" => "Espresso with a dash of foamed milk.",
                    "price" => 3.25,
                    "img" => "images/Macchiato.jpg"
                ],
            ];
            ?>

            <div class="row">
                <?php foreach ($coffeeItems as $item): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <img src="<?= $item['img'] ?>" class="card-img-top" alt="<?= $item['name'] ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $item['name'] ?></h5>
                                <p class="card-text"><?= $item['desc'] ?></p>
                                <p class="card-text fw-bold">$<?= number_format($item['price'], 2) ?></p>
                                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">Order Now</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </main>

    <footer class="bg-dark text-light py-4 text-center">
        <p>&copy; 2025 Brewtopia. All rights reserved.</p>
    </footer>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" class="modal-content needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="loginLabel">Login to Brewtopia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email or Username</label>
                        <input type="text" class="form-control" name="email" id="email" required />
                        <div class="invalid-feedback">Please enter your email or username.</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required />
                        <div class="invalid-feedback">Please enter your password.</div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" name="admin" id="adminCheck" />
                        <label class="form-check-label" for="adminCheck">Login as admin</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Login</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>

</body>

</html>