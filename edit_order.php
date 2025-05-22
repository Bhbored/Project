<?php
session_start();
$conn = new mysqli('sqlXXX.infinityfree.com', 'root', '', 'brewtopia');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_item = intval($_POST['item_id']);
    $new_qty = intval($_POST['quantity']);
    $stmt = $conn->prepare("UPDATE orders SET item_id = ?, quantity = ? WHERE id = ?");
    $stmt->bind_param("iii", $new_item, $new_qty, $id);
    $stmt->execute();
    header("Location: orders.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

$items = $conn->query("SELECT id, name FROM items");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body class="container py-4">
    <h2>Edit Order #<?= $id ?></h2>
    <form method="POST">
        <div class="mb-3">
            <label for="item_id">Item</label>
            <select name="item_id" id="item_id" class="form-select">
                <?php while ($item = $items->fetch_assoc()): ?>
                    <option value="<?= $item['id'] ?>" <?= $item['id'] == $order['item_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($item['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="quantity">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="<?= $order['quantity'] ?>" min="1" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Order</button>
        <a href="orders.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>

</html>