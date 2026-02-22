<?php
$conn = new mysqli("localhost", "root", "", "pos_system");
$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Products - Cashier Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2c2c2c;
            color: white;
        }
        .container {
            margin-top: 40px;
        }
        .table {
            color: white;
        }
        .table th {
            background-color: #a491d3;
        }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        .btn-back {
            background-color: #a491d3;
            color: white;
        }
        .btn-back:hover {
            background-color: #8b7abf;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-center" style="color: #a491d3;">Available Products</h2>

    <?php if ($products->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Price (USD)</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $products->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php if (!empty($row['image'])): ?>
                                    <img src="uploads/<?= htmlspecialchars($row['image']) ?>" class="product-img">
                                <?php else: ?>
                                    <img src="uploads/default.png" class="product-img">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td>$<?= number_format($row['price'], 2) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">No products found.</div>
    <?php endif; ?>

    <div class="mt-4 text-end">
        <a href="cashier_dashboard.php" class="btn btn-back">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>
