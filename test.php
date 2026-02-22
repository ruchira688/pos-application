<?php
$password = 'admin11';
$hash = '$2y$10$J6kFXtKc.lNwo9AhdTyGeurKlG3bqXZaMlpISQJyN5C0nZrDErLVa';

if (password_verify($password, $hash)) {
    echo "Password matches";
} else {
    echo "Password does NOT match";
}
?>
