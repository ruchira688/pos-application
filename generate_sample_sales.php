<?php
require_once 'config.php';

// Number of fake sales per product
$sales_per_product = 3;

// Available payment methods
$payment_methods = ['Cash', 'Card', 'UPI'];

// Fetch all products
$product_query = "SELECT id, price FROM products";
$product_result = $conn->query($product_query);

if ($product_result->num_rows > 0) {
    while ($product = $product_result->fetch_assoc()) {
        $product_id = $product['id'];
        $price = $product['price'];

        // Generate multiple fake sales for each product
        for ($i = 0; $i < $sales_per_product; $i++) {
            $quantity = rand(1, 10); // Random quantity between 1–10
            $payment_method = $payment_methods[array_rand($payment_methods)];
            $sale_date = date('Y-m-d H:i:s', strtotime("-" . rand(0, 30) . " days"));

            $insert_query = $conn->prepare("
                INSERT INTO sales (product_id, quantity, sale_date, price, payment_method)
                VALUES (?, ?, ?, ?, ?)
            ");
            $insert_query->bind_param("iisds", $product_id, $quantity, $sale_date, $price, $payment_method);
            $insert_query->execute();
        }
    }

    echo "<h3 style='color: green;'>✅ Sample sales inserted successfully!</h3>";
} else {
    echo "<h3 style='color: red;'>⚠️ No products found in the database.</h3>";
}

$conn->close();
?>
