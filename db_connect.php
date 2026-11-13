<?php
$host = "sql209.infinityfree.com";
$dbname = "if0_43402784_supplier_db";
$username = "if0_43402784";
$password = "B5YES1lYVF";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>