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

// Handle stock update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $item_id = intval($_POST['item_id']);
    $new_stock = intval($_POST['stock']);

    $stmt = $conn->prepare("UPDATE items SET stock = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_stock, $item_id);
    $stmt->execute();
    $stmt->close();

    header("Location: inventory.php");
    exit;
}

$sql = "SELECT * FROM items ORDER BY name ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Brewtopia - Inventory</title>
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
                    <li class="nav-item"><a class="nav-link" href="orders.php">Orders</a></li>
                    <li class="nav-item"><a class="nav-link active" href="inventory.php">Inventory</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_menu.php">Manage Menu</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        <h2>Inventory Management</h2>
        <?php if ($result && $result->num_rows > 0): ?>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price ($)</th>
                        <th>Stock</th>
                        <th>Update Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= htmlspecialchars($item['description']) ?></td>
                            <td><?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['stock'] ?></td>
                            <td>
                                <form method="POST" class="d-flex gap-2 align-items-center">
                                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                    <input type="number" name="stock" min="0" value="<?= $item['stock'] ?>" class="form-control" style="max-width: 80px;" required />
                                    <button type="submit" name="update_stock" class="btn btn-primary btn-sm">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No items found in inventory.</p>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>