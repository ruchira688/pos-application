<?php
session_start();

// Check if cashier is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'cashier') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cashier Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #9b86c5;
        }
        .container {
            background-color: #2c2c2c;
            color: white;
            padding: 40px;
            margin-top: 50px;
            border-radius: 10px;
        }
        .dashboard-title {
            text-align: center;
            margin-bottom: 30px;
        }
        .dashboard-buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .btn-dashboard {
            background-color: #9b86c5;
            color: black;
            font-size: 18px;
            padding: 15px 25px;
            border-radius: 8px;
            width: 220px;
            text-align: center;
            transition: 0.3s;
        }
        .btn-dashboard:hover {
            background-color: #b9a8e5;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="dashboard-title">Cashier Dashboard</h1>

    <div class="dashboard-buttons">
        <a href="view_products.php" class="btn btn-dashboard">View Products</a>
        <a href="sales_history.php" class="btn btn-dashboard">Sales History</a>
        <a href="change_password.php" class="btn btn-dashboard">Change Password</a>
        <a href="logout.php" class="btn btn-dashboard">Logout</a>
    </div>
</div>

</body>
</html>
