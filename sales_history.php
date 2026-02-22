<?php
$conn = new mysqli("localhost", "root", "", "pos_system");
$sales = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales History - Cashier</title>
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
        .btn-back {
            background-color: #a491d3;
            color: white;
        }
        .btn-back:hover {
            background-color: #8b7abf;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-warning {
            background-color: #ffc107;
            color: black;
        }
        .badge-danger {
            background-color: #dc3545;
        }
        a.order-link {
            color: #a491d3;
            text-decoration: none;
        }
        a.order-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="mb-4 text-center" style="color: #a491d3;">Sales History</h1>

    <?php if ($sales->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total Amount (USD)</th>
                        <th>Status</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $sales->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['order_id']) ?></td>
                            <td><?= htmlspecialchars($row['customer_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($row['order_date']) ?></td>
                            <td>$<?= number_format($row['total_amount'], 2) ?></td>
                            <td>
                                <?php
                                    $status = strtolower($row['status']);
                                    $badge = 'secondary';
                                    if ($status === 'placed') $badge = 'success';
                                    elseif ($status === 'pending') $badge = 'warning';
                                    elseif ($status === 'cancelled') $badge = 'danger';
                                ?>
                                <span class="badge bg-<?= $badge ?>"><?= ucfirst($status) ?></span>
                            </td>
                            <td>
                                <a href="view_order.php?order_id=<?= urlencode($row['order_id']) ?>" class="order-link">View</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No sales history found.</div>
    <?php endif; ?>

    <div class="mt-4 text-end">
        <a href="cashier_dashboard.php" class="btn btn-back">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>
