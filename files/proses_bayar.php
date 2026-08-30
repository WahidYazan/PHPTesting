<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id_pembayaran'])) {
    header('Location: bayar.php');
    exit;
}

$id_pembayaran    = $_POST['id_pembayaran'];
$metode_pembayaran = $_POST['metode_pembayaran'] ?? 'cash';

try {
    $stmt = $pdo->prepare(
        "UPDATE pembayaran
         SET status = 'lunas', waktu_pembayaran = NOW(), metode_pembayaran = ?
         WHERE id_pembayaran = ?"
    );
    $stmt->execute([$metode_pembayaran, $id_pembayaran]);
    header('Location: bayar.php');
    exit;
} catch (\PDOException $e) {
    header('Location: bayar.php?error=' . urlencode('Gagal update pembayaran: ' . $e->getMessage()));
    exit;
}
