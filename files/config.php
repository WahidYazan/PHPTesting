<?php
// ==========================================================
// KONFIGURASI KONEKSI DATABASE
// Sesuaikan jika host/port/user/password Anda berbeda
// ==========================================================
$host    = 'localhost';   // biasanya localhost atau 127.0.0.1
$port    = '3306';        // port default MariaDB
$db      = 'parkir';
$user    = 'root';
$pass    = 'ServBay.dev';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('Koneksi database gagal: ' . $e->getMessage() .
        '<br>Cek kembali host/port/username/password di config.php');
}
