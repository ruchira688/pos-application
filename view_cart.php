<?php
session_start();
$conn = new mysqli("localhost", "root", "", "pos_system");

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$products = [];

if (!empty($cart)) {
    $ids = implode(",", array_keys($cart));
    $result = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
    while ($row = $result->fetch_assoc()) {
        $products[$row['id']] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #2c2c2c; }
        .btn-lavender { background-color: #a491d3; color: white; }
        .btn-lavender:hover { background-color: #8c7bc4; }
        .table img { height: 60px; object-fit: cover; }
    </style>
</head>
<body>

<div class="container py-5">
    <h1 style="color: #a491d3;">Shopping Cart</h1>


    <?php if (empty($cart)): ?>
        <div class="alert alert-info">Your cart is empty.</div>
    <?php else: ?>
        <form action="checkout.php" method="post">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($cart as $id => $qty): ?>
                        <?php if (isset($products[$id])): ?>
                            <?php
                            $p = $products[$id];
                            $subtotal = $p['price'] * $qty;
                            $total += $subtotal;
                            ?>
                            <tr>
                                <td><?php echo $p['name']; ?></td>
                                <td><img src="uploads/<?php echo $p['image']; ?>" width="60"></td>
                                <td>$<?php echo number_format($p['price'], 2); ?></td>
                                <td><?php echo $qty; ?></td>
                                <td>$<?php echo number_format($subtotal, 2); ?></td>
                                <td><a href="remove_from_cart.php?id=<?php echo $id; ?>" class="btn btn-lavender">Remove</a></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <tr>
                        <th colspan="4" class="text-end">Total</th>
                        <th colspan="2">$<?php echo number_format($total, 2); ?></th>
                    </tr>
                </tbody>
            </table>

            <div class="text-end">
                <button type="submit" class="btn btn-lavender">check out</button>
            </div>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
