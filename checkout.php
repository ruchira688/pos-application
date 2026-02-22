<?php
session_start();
$conn = new mysqli("localhost", "root", "", "pos_system");

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Cart is empty. <a href='customer_interface.php'>Go back</a>";
    exit();
}

$cart = $_SESSION['cart'];
$products = [];

// Retrieve products based on cart items
$ids = implode(",", array_keys($cart));
$result = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
while ($row = $result->fetch_assoc()) {
    $products[$row['id']] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if form fields are set
    if (isset($_POST['name'], $_POST['address'], $_POST['payment_mode'])) {
        $name = $_POST['name'];
        $address = $_POST['address'];
        $payment_mode = $_POST['payment_mode'];
        $total = 0;

        // Insert order into orders table
        $order_id = uniqid('ORD');
        $order_date = date('Y-m-d');
        $status = "placed"; // Initially status is "placed"
        
        // Insert order data into orders table
        $stmt = $conn->prepare("INSERT INTO orders (order_id, customer_name, order_date, total_amount, status, delivery_address, payment_mode) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $order_id, $name, $order_date, $total, $status, $address, $payment_mode);

        $stmt->execute();

        // Insert order items into order_items table
        foreach ($cart as $id => $qty) {
            $product = $products[$id];
            $subtotal = $product['price'] * $qty;
            $total += $subtotal;

            // Insert order items into the order_items table
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("siidd", $order_id, $product['id'], $qty, $product['price'], $subtotal);
            $stmt->execute();
        }

        // Update total amount in orders table
        $stmt = $conn->prepare("UPDATE orders SET total_amount = ? WHERE order_id = ?");
        $stmt->bind_param("ds", $total, $order_id);
        $stmt->execute();

        // Clear the cart after order
        $_SESSION['cart'] = [];

        // Show confirmation message
        echo "<h1>Thank you, $name!</h1>";
        echo "<p>Your order has been placed successfully using <strong>$payment_mode</strong>.</p>";
        echo "<p>Delivery address: $address</p>";
        echo "<a href='customer_interface.php'>Continue Shopping</a>";
        echo "<p><a href='view_order.php?order_id=$order_id'>View Order Details</a></p>";

        exit();
    } else {
        echo "Please fill in all the fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #2c2c2c; }
        .btn-lavender { background-color: #a491d3; color: white; }
        .btn-lavender:hover { background-color: #8c7bc4; }
    </style>
</head>
<body>
<div class="container py-5">
    <h1 class="mb-4" style="color: #9b86c5;">Checkout</h1>
    <form method="post">
        <div class="mb-3">
            <label class="form-label" style="color: #9b86c5;">Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label" style="color: #9b86c5;">Delivery Address</label>
            <textarea name="address" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label" style="color: #9b86c5;">Payment Method</label>
            <select name="payment_mode" class="form-select" required>
                <option value="Cash on Delivery">Cash on Delivery</option>
                <option value="Credit Card">Credit Card</option>
                <option value="UPI">UPI</option>
            </select>
        </div>

        <h5 style="color: #9b86c5;">Order Summary</h5>
        <ul class="list-group mb-3">
            <?php $total = 0; ?>
            <?php foreach ($cart as $id => $qty): ?>
                <?php
                    $p = $products[$id];
                    $subtotal = $p['price'] * $qty;
                    $total += $subtotal;
                ?>
                <li class="list-group-item d-flex justify-content-between">
                    <div>
                        <?php echo $p['name']; ?> x <?php echo $qty; ?>
                    </div>
                    <span>$<?php echo number_format($subtotal, 2); ?></span>
                </li>
            <?php endforeach; ?>
            <li class="list-group-item d-flex justify-content-between">
                <strong>Total</strong>
                <strong>$<?php echo number_format($total, 2); ?></strong>
            </li>
        </ul>

        <button type="submit" class="btn btn-lavender">Place Order</button>
    </form>
</div>
</body>
</html>  