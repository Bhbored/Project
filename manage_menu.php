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

// Handle create item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_item'])) {
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    if ($name && $price >= 0 && $stock >= 0) {
        $stmt = $conn->prepare("INSERT INTO items (name, description, price, stock) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdi", $name, $desc, $price, $stock);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: manage_menu.php");
    exit;
}

// Handle delete item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $item_id = intval($_POST['item_id']);
    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_menu.php");
    exit;
}

// Handle update item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_item'])) {
    $item_id = intval($_POST['item_id']);
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    if ($name && $price >= 0 && $stock >= 0) {
        $stmt = $conn->prepare("UPDATE items SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?");
        $stmt->bind_param("ssdii", $name, $desc, $price, $stock, $item_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: manage_menu.php");
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
    <title>Brewtopia - Manage Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
<?php include 'flexible components/navbar.php'; ?>

    <main class="container my-4">
        <h2>Manage Menu</h2>

        <h4>Add New Item</h4>
        <form method="POST" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="text" name="name" placeholder="Name" class="form-control" required />
            </div>
            <div class="col-md-4">
                <input type="text" name="description" placeholder="Description" class="form-control" />
            </div>
            <div class="col-md-2">
                <input type="number" name="price" step="0.01" min="0" placeholder="Price" class="form-control" required />
            </div>
            <div class="col-md-2">
                <input type="number" name="stock" min="0" placeholder="Stock" class="form-control" required />
            </div>
            <div class="col-md-1 d-grid">
                <button type="submit" name="create_item" class="btn btn-success">Add</button>
            </div>
        </form>

        <?php if ($result && $result->num_rows > 0): ?>
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price ($)</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $result->fetch_assoc()): ?>
                        <tr>
                            <form method="POST">
                                <td>
                                    <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" class="form-control" required />
                                </td>
                                <td>
                                    <input type="text" name="description" value="<?= htmlspecialchars($item['description']) ?>" class="form-control" />
                                </td>
                                <td>
                                    <input type="number" name="price" step="0.01" min="0" value="<?= number_format($item['price'], 2) ?>" class="form-control" required />
                                </td>
                                <td>
                                    <input type="number" name="stock" min="0" value="<?= $item['stock'] ?>" class="form-control" required />
                                </td>
                                <td class="d-flex gap-2">
                                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>" />
                                    <button type="submit" name="update_item" class="btn btn-primary btn-sm">Update</button>
                                    <button type="submit" name="delete_item" class="btn btn-danger btn-sm" onclick="return confirm('Delete this item?');">Delete</button>
                                </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No menu items found.</p>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/scripts.js"></script>
</body>

</html>