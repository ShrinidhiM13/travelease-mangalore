<?php
// config/db.php
$host = 'sql213.infinityfree.com';
$db   = 'if0_38678399_tm';
$user = 'if0_38678399';
$pass = '6oh9qJmrXPB'; // your XAMPP MySQL password
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass, $options);
} catch (PDOException $e) {
    exit('Database connection failed: ' . $e->getMessage());
}
?>
