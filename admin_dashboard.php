 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #9b86c5; /* Lavender */
            color: black;
        }
        .dashboard-container {
            max-width: 900px;
            margin: 80px auto;
            padding: 50px;
            background-color: #1c1c1e; /* Light black */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        h2 {
            text-align: center;
            margin-bottom: 50px;
            color: white;
        }
        .dashboard-section {
            margin-bottom: 60px;
        }
        .btn-dashboard {
            width: 100%;
            height: 50px;
            background-color: #9b86c5; /* Light lavender */
            color: #000;
            font-size: 25px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
        }
        .btn-dashboard:hover {
            background-color: #d1c4e9;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color:#1c1c1e; /* Lavender */
            color: white;
            font-size: 25px;
            font-weight: bold;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
        }
        .logout-btn:hover {
            background-color:#E6E6FA;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
    <a href="logout.php" class="logout-btn">Logout</a>
        <h2>Admin Dashboard</h2>

        <div class="dashboard-section">
            <a href="manage_products.php" class="btn btn-dashboard">Manage Products</a>
        </div>
        <div class="dashboard-section">
            <a href="manage_customers.php" class="btn btn-dashboard">Manage Customers</a>
        </div>
        <div class="dashboard-section">
            <a href="sales_report.php" class="btn btn-dashboard">Sales Report</a>
        </div>
  </div>

</body>
</html>
