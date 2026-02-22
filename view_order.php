<?php
// Establish connection to MySQL database
$conn = new mysqli("localhost", "root", "", "pos_system");

// Check for valid order ID from the URL
$order_id = $_GET['order_id'] ?? '';
if (!$order_id) {
    echo "Order ID missing.";
    exit();
}

// Prepare and execute the SQL query to fetch the order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmt->bind_param("s", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// If order not found, display message
if (!$order) {
    echo "Order not found.";
    exit();
}

// Prepare and execute the SQL query to fetch ordered items for the order
$stmt_items = $conn->prepare("SELECT oi.*, p.name AS product_name, p.price AS product_price 
                              FROM order_items oi
                              JOIN products p ON oi.product_id = p.id
                              WHERE oi.order_id = ?");
$stmt_items->bind_param("s", $order_id);
$stmt_items->execute();
$order_items = $stmt_items->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Order - POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f3fb;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #a491d3;
            color: white;
            padding: 20px;
            border-radius: 12px 12px 0 0;
            font-size: 1.5rem;
        }
        .btn-back {
            background-color: #a491d3;
            color: white;
        }
        .btn-back:hover {
            background-color: #8a78bd;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="card">
        <div class="header text-center">
            Order Summary: <?php echo htmlspecialchars($order['order_id']); ?>
        </div>
        <div class="card-body">
            <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
            <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
            <p><strong>Total Amount:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>

            <!-- Displaying Delivery Address -->
            <p><strong>Delivery Address:</strong> <?php echo $order['delivery_address'] ? htmlspecialchars($order['delivery_address']) : "Not Provided"; ?></p>

            <!-- Displaying Payment Mode -->
            <p><strong>Payment Method:</strong> <?php echo $order['payment_mode'] ? htmlspecialchars($order['payment_mode']) : "Not Provided"; ?></p>

            <!-- Displaying Ordered Items -->
            <h5>Ordered Items:</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_ordered_amount = 0;
                    while ($item = $order_items->fetch_assoc()) {
                        $item_total = $item['quantity'] * $item['product_price'];
                        $total_ordered_amount += $item_total;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>$<?php echo number_format($item['product_price'], 2); ?></td>
                            <td>$<?php echo number_format($item_total, 2); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <p><strong>Total for Ordered Items:</strong> $<?php echo number_format($total_ordered_amount, 2); ?></p>

            <hr>
            <a href="customer_interface.php" class="btn btn-back mt-2">← Back to Shop</a>
        </div>
    </div>
</div>
</body>
</html>
