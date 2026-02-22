<?php
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php if (isset($_GET['error']) && $_GET['error'] === 'exists'): ?>
    <div class="alert alert-dismissible fade show" role="alert" style="background-color: #000; color: white; font-weight: bold; font-size: 18px; border-left: 5px solid #9b86c5; margin: 20px;">
        Product already exists!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: white;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #000; color: white; font-weight: bold; font-size: 18px; border-left: 5px solid #9b86c5; margin: 20px;">
        Product added successfully!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: white;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $price = (float)$_POST['price'];
    $quantity = (int)$_POST['quantity'];
    $image = '';

    if (!empty($_FILES['image']['name'])) {
        $image = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image);
    }

    $check_stmt = $conn->prepare("SELECT id FROM products WHERE name = ?");
    $check_stmt->bind_param("s", $name);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        header("Location: manage_products.php?error=exists");
        exit;
    }
    $check_stmt->close();

    $stmt = $conn->prepare("INSERT INTO products (name, price, quantity, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdis", $name, $price, $quantity, $image); // FIXED LINE

    if (!$stmt->execute()) {
        die("Error: " . $stmt->error);
    }
    $stmt->close();

    header("Location: manage_products.php?success=1");
    exit;
}

if (isset($_POST['update'])) {
    $id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = (float)$_POST['price'];
    $quantity = (int)$_POST['quantity'];

    if (!empty($_FILES['image']['name'])) {
        $image = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image);
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, quantity=?, image=? WHERE id=?");
        $stmt->bind_param("sdisi", $name, $price, $quantity, $image, $id);
    } else {
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, quantity=? WHERE id=?");
        $stmt->bind_param("sdis", $name, $price, $quantity, $id);
    }

    if (!$stmt->execute()) {
        die("Error: " . $stmt->error);
    }
    $stmt->close();

    header("Location: manage_products.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $conn->prepare("SELECT image FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();

    if ($image && file_exists("uploads/$image")) {
        unlink("uploads/$image");
    }

    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: manage_products.php");
    } else {
        echo "<script>alert('Cannot delete product due to existing sales.'); window.location='manage_products.php';</script>";
    }
    $stmt->close();
    exit;
}

$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
 .modal-content {    
    background-color:#2c2c2c;
    color: white;
    border-radius: 10px;
    font-size: 16px;
    padding: 90px;
    border: 4px solid #ccc;
}
.form-control, .form-control-file {
    font-size: 18px;
    color:black;
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    font-weight:bold;
    margin-left:20px;
    text-align:center;
}
.form-control::placeholder {
    color: black;
}
label {
    font-size: 10px;
    color: black;
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
.tile {
    font-size:15px;
    background-color: #3e3e3e;
    border-radius: 20px;
    padding: 15px;
    margin: 15px;
    text-align: center;
}
.tile img {
    width: 90%;
    height: 200px;
    object-fit: cover;
}
.tile-title {
    font-size: 2rem;
    color: white;
}
.btn-lavender {
    background-color: #9b86c5;
    color: black;
    width:130px;
    padding:8px;
    font-size:18px;
}
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center mb-4" style="margin-top: -30px;">Manage Products</h1>

    <form class="custom-form" action="" method="POST" enctype="multipart/form-data" class="mb-4">
    <div class="form-row">
        <div class="col">
            <input type="text" name="name" class="form-control" placeholder="  Name" required>
        </div>
        <div class="col">
            <input type="number" step="0.01" name="price" class="form-control" placeholder="  Price" required>
        </div>
        <div class="col">
            <input type="number" name="quantity" class="form-control" placeholder="Quantity" required>
        </div>
        <div class="col" style="margin-left: 380px;">
            <label class="btn btn-lavender btn-block">
                Upload Image <input type="file" name="image" hidden>
            </label>
        </div>
        <div class="col" style="margin-left: 20px;">
            <button type="submit" name="add" class="btn btn-lavender btn-block">Add product</button>
        </div>
    </div>
    </form>

    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-4">
            <div class="tile">
                <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                <div class="tile-title"><?= htmlspecialchars($row['name']) ?></div>
                <div>Price:&#8377; <?= number_format($row['price'], 2) ?></div>
                <div>Quantity: <?= htmlspecialchars($row['quantity']) ?></div>
                <button class="btn btn-lavender" data-toggle="modal" data-target="#editModal<?= $row['id'] ?>">Edit</button>
                <a href="?delete=<?= $row['id'] ?>" class="btn btn-lavender" onclick="return confirm('Delete this product?')">Delete</a>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h1 class="modal-title text-center w-100">Edit Product</h1>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                            <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" class="form-control mb-2" required>
                            <input type="number" step="0.01" name="price" value="<?= $row['price'] ?>" class="form-control mb-2" required>
                            <input type="number" name="quantity" value="<?= $row['quantity'] ?>" class="form-control mb-2" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="update" class="btn btn-lavender">Update</button>
                            <label class="btn btn-lavender btn-block">
                                Upload <input type="file" name="image" hidden>
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <a href="logout.php" class="btn btn-lavender";>Logout</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
