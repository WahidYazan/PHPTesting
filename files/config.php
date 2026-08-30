<?php
// ==========================================================
// KONFIGURASI KONEKSI DATABASE
// Sesuaikan jika host/port/user/password Anda berbeda
// ==========================================================
$host = 'rvfsebslshfoekonp9k6gc7h'; // Atau gunakan IP publik VPS kamu
$port = '3306';                    // Sesuaikan dengan public port yang aktif di Coolify
$db = 'default';
$user = 'mariadb';
$pass = 'Wahid';
$charset = 'utf8mb4'; // Biarin permanentseperti ini

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
