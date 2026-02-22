<?php
$adminPassword = 'admin123';
$cashierPassword = 'cashier123';

$adminHashedPassword = password_hash($adminPassword, PASSWORD_BCRYPT);
$cashierHashedPassword = password_hash($cashierPassword, PASSWORD_BCRYPT);

echo "Admin Password Hash: " . $adminHashedPassword . "<br>";
echo "Cashier Password Hash: " . $cashierHashedPassword;
?>
