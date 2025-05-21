<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'brewtopia');
if ($conn->connect_error) {
    die('DB connection failed: ' . $conn->connect_error);
}

// Handle confirm order (update status and reduce stock)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    $order_id = intval($_POST['order_id']);

    // Get order details
    $stmt = $conn->prepare("SELECT item_id, quantity FROM orders WHERE id = ? AND status = 'Pending'");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($order) {
        $item_id = $order['item_id'];
        $quantity = $order['quantity'];

        // Update inventory
        $stmt2 = $conn->prepare("UPDATE items SET stock = stock - ? WHERE id = ? AND stock >= ?");
        $stmt2->bind_param("iii", $quantity, $item_id, $quantity);
        $stmt2->execute();

        if ($stmt2->affected_rows > 0) {
            // Update order status
            $stmt3 = $conn->prepare("UPDATE orders SET status = 'Confirmed' WHERE id = ?");
            $stmt3->bind_param("i", $order_id);
            $stmt3->execute();
            $stmt3->close();
        }
        $stmt2->close();
    }

    header("Location: orders.php");
    exit;
}

// Fetch orders with item names
$sql = "SELECT o.id, i.name AS item_name, o.quantity, o.order_time, o.status
        FROM orders o
        JOIN items i ON o.item_id = i.id
        ORDER BY o.order_time DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Brewtopia - Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">Brewtopia Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAdmin">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAdmin">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="admin.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="orders.php">Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="inventory.php">Inventory</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_menu.php">Manage Menu</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        <h2>Orders</h2>
        <?php if ($result && $result->num_rows > 0): ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Order Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['item_name']) ?></td>
                            <td><?= $order['quantity'] ?></td>
                            <td><?= $order['order_time'] ?></td>
                            <td><?= $order['status'] ?></td>
                            <td>
                                <?php if ($order['status'] === 'Pending'): ?>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <button type="submit" name="confirm_order" class="btn btn-sm btn-success">Confirm</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">Confirmed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No orders yet.</p>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>