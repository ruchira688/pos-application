<?php
session_start();
$conn = new mysqli("localhost", "root", "", "pos_system");

// Add to cart logic
if (isset($_GET['add'])) {
    $product_id = $_GET['add'];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if already in cart
    if (!array_key_exists($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][$product_id] = 1;
    } else {
        $_SESSION['cart'][$product_id]++;
    }

    header("Location: customer_interface.php");
    exit();
}

// Fetch products
$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Interface</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f9f7fc; }
        .card { border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .btn-lavender { background-color: #a491d3; color: white; }
        .btn-lavender:hover { background-color: #8d7cc2; }
        footer { background-color: #a491d3; color: white; padding: 40px 20px; }
        footer a { color: white; text-decoration: none; }
        footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<!-- View Cart Button -->
<div class="container py-3 d-flex justify-content-end">
    <a href="view_cart.php" class="btn btn-lavender">
        🛒 View Cart
        <?php
        $total_items = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $qty) {
                $total_items += $qty;
            }
        }
        echo " ($total_items)";
        ?>
    </a>
</div>

<!-- Product Listing -->
<div class="container py-3">
    <h1 class="mb-4 text-center">Shop Products</h1>
    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <img src="uploads/<?php echo $row['image']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['name']; ?></h5>
                    <p class="card-text">$<?php echo number_format($row['price'], 2); ?></p>
                    <a href="?add=<?php echo $row['id']; ?>" class="btn btn-lavender w-100">Add to Cart</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Track Order -->
<div class="container mb-5">
    <h4 class="mb-3">Track Your Order</h4>
    <form action="track_order.php" method="post" class="row g-3">
        <div class="col-md-6">
            <input type="text" name="order_id" class="form-control" placeholder="Enter Order ID" required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-lavender">Track Order</button>
        </div>
    </form>
</div>

<!-- Footer -->
<footer class="mt-auto">
    <div class="row">
        <div class="col-md-3">
            <h5>NEED HELP</h5>
            <ul class="list-unstyled">
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Track Order</a></li>
                <li><a href="#">Returns & Refunds</a></li>
                <li><a href="#">FAQs</a></li>
                <li><a href="#">My Account</a></li>
            </ul>
        </div>
        <div class="col-md-3">
            <h5>COMPANY</h5>
            <ul class="list-unstyled">
                <li><a href="#">About Us</a></li>
                <li><a href="#">Investor Relation</a></li>
                <li><a href="#">Careers</a></li>
                <li><a href="#">Gift Vouchers</a></li>
                <li><a href="#">Community Initiatives</a></li>
            </ul>
        </div>
        <div class="col-md-3">
            <h5>MORE INFO</h5>
            <ul class="list-unstyled">
                <li><a href="#">T&C</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Sitemap</a></li>
                <li><a href="#">Get Notified</a></li>
                <li><a href="#">Blogs</a></li>
            </ul>
        </div>
        <div class="col-md-3">
            <h5>STORE NEAR ME</h5>
            <ul class="list-unstyled">
                <li>Gandhinagar</li>
                <li>Jodhpur</li>
                <li>Vadodara</li>
                <li>Mumbai</li>
                <li><a href="#">View More?</a></li>
            </ul>
        </div>
    </div>
</footer>

</body>
</html>
