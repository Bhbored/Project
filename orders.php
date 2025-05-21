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
    $stmt = $conn->prepare("SELECT item_id, quantity FROM orders WHERE id = ? AND status = 'pending'");
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
            $stmt3 = $conn->prepare("UPDATE orders SET status = 'confirmed' WHERE id = ?");
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
    <?php include 'flexible components/navbar.php'; ?>

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
                                <?php if ($order['status'] === 'pending'): ?>
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to confirm this order?')" style="display:inline;">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <button type="submit" name="confirm_order" class="btn btn-sm btn-success">Confirm</button>
                                    </form>
                                    <a href="edit_order.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-warning ms-1">Edit</a>
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