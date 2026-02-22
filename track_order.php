<?php
$conn = new mysqli("localhost", "root", "", "pos_system");

$order_id = '';
$order = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Trim and escape the input to avoid trailing spaces and SQL injection
    $order_id = trim($conn->real_escape_string($_POST['order_id']));

    // Search for exact match in order_id
    $query = "SELECT * FROM orders WHERE order_id = '$order_id'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $order = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Track Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2c2c2c;
            color: white;
        }
        .container {
            max-width: 600px;
            margin-top: 60px;
        }
        .btn-lavender {
            background-color: #a491d3;
            color: white;
        }
        .btn-lavender:hover {
            background-color: #8c7bc4;
        }
        .card {
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            background-color: #3a3a3a;
        }
        .form-control {
            background-color: #444;
            border: 1px solid #666;
            color: white;
        }
        .form-control::placeholder {
            color: #aaa;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center mb-4" style="color: #a491d3;">Track Your Order</h1>

    <form method="post" action="track_order.php" class="mb-4">
        <div class="input-group">
            <input type="text" name="order_id" class="form-control" placeholder="Enter your Order ID" required value="<?php echo htmlspecialchars($order_id); ?>">
            <button type="submit" class="btn btn-lavender">Track</button>
        </div>
    </form>

    <?php if ($order): ?>
        <div class="card p-4">
            <h5 class="text-white">Order ID: <?php echo htmlspecialchars($order['order_id']); ?></h5>
           <p style="color: white;"><strong>Customer:</strong> <?php echo htmlspecialchars($order['customer_name'] ?? 'N/A'); ?></p>

            <p style="color: white;"><strong>Status:</strong> 
                <span class="badge bg-<?php echo ($order['status'] == 'Placed' || $order['status'] == 'Completed') ? 'success' : 'warning'; ?>">
                    <?php echo htmlspecialchars($order['status'] ?? 'Pending'); ?>
                </span>
            </p>
            <p style="color: white;"><strong>Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
            <p style="color: white;"><strong>Total:</strong> $<?php echo number_format($order['total_amount'], 2); ?></p>
            <p style="color: white;"><strong>Payment Mode:</strong> <?php echo htmlspecialchars($order['payment_mode'] ?? 'N/A'); ?></p>
            <p style="color: white;"><strong>Delivery Address:</strong> <?php echo htmlspecialchars($order['delivery_address'] ?? 'N/A'); ?></p>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        <div class="alert alert-danger">Order not found. Please check your Order ID.</div>
    <?php endif; ?>
</div>
</body>
</html>
