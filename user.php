<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: index.php');
    exit;
}

// Connect to DB
$conn = new mysqli('localhost', 'root', '', 'brewtopia');
if ($conn->connect_error) {
    die('DB connection failed: ' . $conn->connect_error);
}

// Load items
$sql = "SELECT * FROM items WHERE stock > 0";
$result = $conn->query($sql);

$cart = $_SESSION['cart'] ?? [];

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $item_id = intval($_POST['item_id']);
    $quantity = 1;

    if (isset($cart[$item_id])) {
        $cart[$item_id] += $quantity;
    } else {
        $cart[$item_id] = $quantity;
    }
    $_SESSION['cart'] = $cart;
    header("Location: user.php");
    exit;
}

// Handle Remove from Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_cart'])) {
    $item_id = intval($_POST['item_id']);
    if (isset($cart[$item_id])) {
        unset($cart[$item_id]);
        $_SESSION['cart'] = $cart;
    }
    header("Location: user.php");
    exit;
}

// Handle Checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    if (!empty($cart)) {
        foreach ($cart as $item_id => $qty) {
            // Insert into orders table
            $stmt = $conn->prepare("INSERT INTO orders (item_id, quantity) VALUES (?, ?)");
            $stmt->bind_param("ii", $item_id, $qty);
            $stmt->execute();
            $stmt->close();
        }
        // Clear cart
        $_SESSION['cart'] = [];
        $checkout_msg = "Thank you for your order! It will be processed soon.";
    }
    header("Location: user.php?checkout=success");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Brewtopia - Browse Coffee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="user.php">Brewtopia</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavUser">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavUser">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="user.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="user.php#menu">Menu</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="#cartModal" data-bs-toggle="modal">
                            Cart
                            <?php if (!empty($cart)) : ?>
                                <span class="cart-count"><?= array_sum($cart) ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4" id="menu">
        <h2 class="mb-4">Browse Our Coffee Menu</h2>
        <div class="row">
            <?php if ($result && $result->num_rows > 0) : ?>
                <?php while ($item = $result->fetch_assoc()) : ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100 d-flex flex-column">
                            <img src="https://source.unsplash.com/400x300/?coffee,<?= $item['id'] ?>" class="card-img-top" alt="<?= htmlspecialchars($item['name']) ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                                <p class="card-text flex-grow-1"><?= htmlspecialchars($item['description']) ?></p>
                                <p class="card-text fw-bold">$<?= number_format($item['price'], 2) ?></p>
                                <form method="POST" class="mt-auto">
                                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>" />
                                    <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <p>No items available.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Your Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if (empty($cart)) : ?>
                        <p>Your cart is empty.</p>
                    <?php else : ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                foreach ($cart as $item_id => $qty):
                                    $stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
                                    $stmt->bind_param("i", $item_id);
                                    $stmt->execute();
                                    $result_item = $stmt->get_result()->fetch_assoc();
                                    $stmt->close();
                                    if (!$result_item) continue;
                                    $subtotal = $result_item['price'] * $qty;
                                    $total += $subtotal;
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($result_item['name']) ?></td>
                                        <td><?= $qty ?></td>
                                        <td>$<?= number_format($result_item['price'], 2) ?></td>
                                        <td>$<?= number_format($subtotal, 2) ?></td>
                                        <td>
                                            <button type="submit" name="remove_from_cart" value="1" class="btn btn-sm btn-danger" formaction="user.php">
                                                <input type="hidden" name="item_id" value="<?= $item_id ?>">
                                                Remove
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th>$<?= number_format($total, 2) ?></th>
                                    <th></th>
                                </tr>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <?php if (!empty($cart)) : ?>
                        <button type="submit" name="checkout" class="btn btn-success">Checkout</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>