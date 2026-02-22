<?php
session_start();
$conn = new mysqli("localhost", "root", "", "pos_system");

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Fetch current password from DB
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($currentPassword, $hashedPassword)) {
        $error = "Current password is incorrect.";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "New passwords do not match.";
    } elseif (strlen($newPassword) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $newHashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateStmt->bind_param("si", $newHashed, $userId);
        if ($updateStmt->execute()) {
            $success = "Password updated successfully!";
        } else {
            $error = "Failed to update password.";
        }
        $updateStmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password - Cashier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2c2c2c;
            color: white;
        }
        .container {
            max-width: 500px;
            margin-top: 80px;
        }
        .form-control {
            background-color: #444;
            color: white;
            border: 1px solid #666;
        }
        .btn-lavender {
            background-color: #a491d3;
            color: white;
        }
        .btn-lavender:hover {
            background-color: #917ec7;
        }
    </style>
</head>
<body>

<div class="container">
    <h3 class="text-center mb-4" style="color: #a491d3;">Change Password</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label>Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <div class="d-grid">
            <button class="btn btn-lavender" type="submit">Update Password</button>
        </div>
    </form>

    <div class="mt-3 text-center">
        <a href="cashier_dashboard.php" class="btn btn-sm btn-outline-light">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>
