<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: tambah_transaksi.php');
    exit;
}

$mode = $_POST['mode'] ?? 'pilih';
$id_slot = $_POST['id_slot'] ?? '';
$id_kendaraan = '';

try {
    $pdo->beginTransaction();

    // Pastikan slot masih tersedia (hindari race condition)
    $cek = $pdo->prepare("SELECT status FROM slot_parkir WHERE id_slot = ? FOR UPDATE");
    $cek->execute([$id_slot]);
    $status = $cek->fetchColumn();

    if ($status !== 'tersedia') {
        $pdo->rollBack();
        header('Location: tambah_transaksi.php?error=' . urlencode('Slot sudah terisi, silakan pilih slot lain'));
        exit;
    }

    if ($mode === 'baru') {
        // Mode input kendaraan baru
        $plat_nomor = $_POST['plat_nomor_baru'] ?? '';
        $nama_pemilik = $_POST['nama_pemilik'] ?? '';
        $id_jenis = $_POST['id_jenis'] ?? '';

        if (!$plat_nomor || !$nama_pemilik || !$id_jenis) {
            $pdo->rollBack();
            header('Location: tambah_transaksi.php?error=' . urlencode('Plat nomor, nama pemilik, dan jenis kendaraan wajib diisi'));
            exit;
        }

        // Cek apakah plat nomor sudah ada
        $cek_plat = $pdo->prepare("SELECT id_kendaraan FROM kendaraan WHERE plat_nomor = ?");
        $cek_plat->execute([$plat_nomor]);
        if ($cek_plat->fetch()) {
            $pdo->rollBack();
            header('Location: tambah_transaksi.php?error=' . urlencode('Plat nomor sudah terdaftar, silakan pilih dari daftar kendaraan'));
            exit;
        }

        // Insert pemilik baru
        $insert_pemilik = $pdo->prepare("INSERT INTO pemilik (nama, no_hp) VALUES (?, ?)");
        $insert_pemilik->execute([$nama_pemilik, '']);
        $id_pemilik = $pdo->lastInsertId();

        // Insert kendaraan baru
        $insert_kendaraan = $pdo->prepare("INSERT INTO kendaraan (plat_nomor, id_pemilik, id_jenis) VALUES (?, ?, ?)");
        $insert_kendaraan->execute([$plat_nomor, $id_pemilik, $id_jenis]);
        $id_kendaraan = $pdo->lastInsertId();

    } else {
        // Mode pilih kendaraan yang sudah ada
        $id_kendaraan = $_POST['id_kendaraan'] ?? '';
        
        if (!$id_kendaraan) {
            $pdo->rollBack();
            header('Location: tambah_transaksi.php?error=' . urlencode('Kendaraan wajib dipilih'));
            exit;
        }
    }

    if (!$id_slot) {
        $pdo->rollBack();
        header('Location: tambah_transaksi.php?error=' . urlencode('Slot wajib dipilih'));
        exit;
    }

    // Insert transaksi parkir
    $insert = $pdo->prepare(
        "INSERT INTO transaksi_parkir (id_kendaraan, id_slot, waktu_masuk, status)
         VALUES (?, ?, NOW(), 'parkir')"
    );
    $insert->execute([$id_kendaraan, $id_slot]);

    // Update status slot
    $update = $pdo->prepare("UPDATE slot_parkir SET status = 'terisi' WHERE id_slot = ?");
    $update->execute([$id_slot]);

    $pdo->commit();
    header('Location: index.php');
    exit;

} catch (\PDOException $e) {
    $pdo->rollBack();
    header('Location: tambah_transaksi.php?error=' . urlencode('Gagal menyimpan: ' . $e->getMessage()));
    exit;
}
