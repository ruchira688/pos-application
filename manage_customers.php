<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Add Customer
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $phone);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_customers.php");
    exit;
}

// Update Customer
if (isset($_POST['update'])) {
    $id = $_POST['customer_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt =$conn->prepare("UPDATE customers SET name=?, email=?, phone=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $phone, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_customers.php");
    exit;
}

// Delete Customer
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM customers WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_customers.php");
    exit;
}

$result = $conn->query("SELECT * FROM customers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Customers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
.modal-content {
    background-color:#e5d4ff; /* Light lavender or any custom color */
}

.modal-header, .modal-footer {
    background-color: #d8c1f1; /* Optional: matching header/footer */
}

.modal-title {
    color: black; /* Optional: title color */
}

    .btn-logout {
        background-color: #dc3545;
        color: white;
    }
   body {
            background-color: #9b86c5;
        }
        .container {
            background-color: #2c2c2c;
            color: white;
            padding: 50px;
            margin-top: 50px;
            border-radius: 10px;
        }
        .form-control {
            font-size: 18px;
            color: black;
            background-color: #f9f9f9;
        }
        label {
            font-size: 14px;
        }
        .btn-lavender {
            background-color: #9b86c5;
            color: black;
            width: 300px;
        }
        .tile {
            background-color: #3e3e3e;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .tile h5 {
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center mb-4">Manage Customers</h1>

    <form method="POST" class="mb-4">
        <div class="form-row">
            <div class="col">
                <input type="text" name="name" class="form-control" placeholder="Customer Name" required>
            </div>
            <div class="col">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="col">
                <input type="text" name="phone" class="form-control" placeholder="Phone" required>
            </div>
            <div class="col">
                <button type="submit" name="add" class="btn btn-lavender">Add</button>
            </div>
        </div>
    </form>

    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="tile">
            <h5><?= htmlspecialchars($row['name']) ?></h5>
            <p>Email: <?= htmlspecialchars($row['email']) ?></p>
            <p>Phone: <?= htmlspecialchars($row['phone']) ?></p>
            <button class="btn btn-lavender" data-toggle="modal" data-target="#editModal<?= $row['id'] ?>">Edit</button>
            <a href="?delete=<?= $row['id'] ?>" class="btn btn-lavender" onclick="return confirm('Delete this customer?')">Delete</a>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h1 class="modal-title mx-auto">Edit Customer</h1>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="customer_id" value="<?= $row['id'] ?>">
                            <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control mb-2" required>
                            <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" class="form-control mb-2" required>
                            <input type="text" name="phone" value="<?= htmlspecialchars($row['phone']) ?>" class="form-control mb-2" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="update" class="btn btn-lavender">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endwhile; ?>

   <div class="mt-4 d-flex justify-content-end gap-2">
    <a href="admin_dashboard.php" class="btn btn-lavender mr-2">← Back</a>
    <a href="logout.php" class="btn btn-lavender">Logout</a>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
