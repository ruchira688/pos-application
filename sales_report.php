<?php
require_once 'config.php';

// Date filter
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Query to include all products, even those with no sales
$sales_query = "
    SELECT p.name, 
           COALESCE(SUM(s.quantity), 0) AS total_quantity, 
           COALESCE(SUM(s.quantity * s.price), 0) AS total_sales
    FROM products p
    LEFT JOIN sales s ON s.product_id = p.id
";

// Apply date filter if provided
if (!empty($start_date) && !empty($end_date)) {
    $sales_query .= " AND DATE(s.sale_date) BETWEEN ? AND ?";
    $sales_query .= " GROUP BY p.name ORDER BY total_sales DESC";
    $stmt = $conn->prepare($sales_query);
    $stmt->bind_param("ss", $start_date, $end_date);
} else {
    $sales_query .= " GROUP BY p.name ORDER BY total_sales DESC";
    $stmt = $conn->prepare($sales_query);
}

$stmt->execute();
$sales_result = $stmt->get_result();
$sales_data = $sales_result->fetch_all(MYSQLI_ASSOC);

// Payment method summary
$payment_query = "
    SELECT payment_method, SUM(quantity * price) AS total
    FROM sales
    " . (!empty($start_date) && !empty($end_date) ? "WHERE DATE(sale_date) BETWEEN '$start_date' AND '$end_date'" : "") . "
    GROUP BY payment_method
";
$payment_result = $conn->query($payment_query);
$payment_data = $payment_result->fetch_all(MYSQLI_ASSOC);

// Top Performing Products - Fetch top 5 products based on sales
$top_performance_query = "
    SELECT p.name, SUM(s.quantity * s.price) AS total_sales
    FROM sales s
    JOIN products p ON s.product_id = p.id
    GROUP BY p.name
    ORDER BY total_sales DESC
    LIMIT 5
";
$top_performance_result = $conn->query($top_performance_query);
$top_performance_data = $top_performance_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #9b86c5;
            color: white;
        }
        .container {
            background-color: #2c2c2c;
            padding: 40px;
            margin-top: 50px;
            border-radius: 10px;
        }
        h1, th, td {
            color: white;
        }
        .form-control {
            background-color: #f9f9f9;
            border: 2px solid #ccc;
            font-size: 18px;
        }
        .btn-lavender {
            background-color: #c3b1e1;
            color: black;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center mb-4">Sales Report</h1>

    <!-- Date Filter -->
    <form method="GET" class="form-inline mb-4">
        <label class="mr-2">Start Date:</label>
        <input type="date" name="start_date" class="form-control mr-3" value="<?= htmlspecialchars($start_date) ?>">
        <label class="mr-2">End Date:</label>
        <input type="date" name="end_date" class="form-control mr-3" value="<?= htmlspecialchars($end_date) ?>">
        <button type="submit" class="btn btn-lavender">Filter</button>
    </form>

    <!-- Sales Table -->
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Total Quantity Sold</th>
                <th>Total Sales ($)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($sales_data)): ?>
                <?php foreach ($sales_data as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= $row['total_quantity'] ?></td>
                        <td><?= number_format($row['total_sales'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">No sales data available.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Payment Method Summary -->
    <h4 class="mt-5">Sales by Payment Method</h4>
    <ul>
        <?php foreach ($payment_data as $method): ?>
            <li><strong><?= $method['payment_method'] ?>:</strong> $<?= number_format($method['total'], 2) ?></li>
        <?php endforeach; ?>
    </ul>

    <!-- Top Performing Products -->
    <h4 class="mt-5">Top 5 Products by Sales</h4>
    <ul>
        <?php foreach ($top_performance_data as $top_product): ?>
            <li><strong><?= $top_product['name'] ?>:</strong> $<?= number_format($top_product['total_sales'], 2) ?></li>
        <?php endforeach; ?>
    </ul>

    <!-- Chart -->
    <canvas id="salesChart" height="120"></canvas>

    <!-- Logout -->
    <div class="mt-4">
        <a href="logout.php" class="btn btn-lavender">Logout</a>
    </div>
</div>

<script>
    const labels = <?= json_encode(array_column($sales_data, 'name')) ?>;
    const data = <?= json_encode(array_column($sales_data, 'total_sales')) ?>;

    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Sales ($)',
                data: data,
                backgroundColor: '#c3b1e1',
                borderColor: '#8c79a4',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

</body>
</html>