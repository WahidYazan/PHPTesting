<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id_transaksi'])) {
    header('Location: checkout.php');
    exit;
}

$id_transaksi = $_POST['id_transaksi'];

try {
    $pdo->beginTransaction();

    // Ambil data transaksi + jenis kendaraan untuk cek tarif
    $sql = "SELECT t.id_slot, t.waktu_masuk, k.id_jenis
            FROM transaksi_parkir t
            JOIN kendaraan k ON k.id_kendaraan = t.id_kendaraan
            WHERE t.id_transaksi = ? AND t.status = 'parkir'
            FOR UPDATE";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_transaksi]);
    $trx = $stmt->fetch();

    if (!$trx) {
        $pdo->rollBack();
        header('Location: checkout.php?error=' . urlencode('Transaksi tidak ditemukan atau sudah selesai'));
        exit;
    }

    // Ambil tarif per jam sesuai jenis kendaraan
    $tarifStmt = $pdo->prepare("SELECT tarif_per_jam FROM tarif WHERE id_jenis = ?");
    $tarifStmt->execute([$trx['id_jenis']]);
    $tarif_per_jam = $tarifStmt->fetchColumn();
    $tarif_per_jam = $tarif_per_jam ?: 0;

    // Hitung durasi (dibulatkan ke atas per jam, minimal 1 jam)
    $masuk  = new DateTime($trx['waktu_masuk']);
    $keluar = new DateTime(); // sekarang
    $selisih_menit = ($keluar->getTimestamp() - $masuk->getTimestamp()) / 60;
    $jam = max(1, (int) ceil($selisih_menit / 60));

    $total_biaya = $jam * $tarif_per_jam;

    // Update transaksi: catat waktu keluar, total biaya, selesaikan status
    $update = $pdo->prepare(
        "UPDATE transaksi_parkir
         SET waktu_keluar = NOW(), total_biaya = ?, status = 'selesai'
         WHERE id_transaksi = ?"
    );
    $update->execute([$total_biaya, $id_transaksi]);

    // Bebaskan slot
    $updateSlot = $pdo->prepare("UPDATE slot_parkir SET status = 'tersedia' WHERE id_slot = ?");
    $updateSlot->execute([$trx['id_slot']]);

    // Buat catatan pembayaran berstatus belum lunas (waktu_pembayaran diisi saat benar-benar dibayar)
    $insertBayar = $pdo->prepare(
        "INSERT INTO pembayaran (id_transaksi, jumlah_bayar, status, waktu_pembayaran)
         VALUES (?, ?, 'belum_lunas', NULL)"
    );
    $insertBayar->execute([$id_transaksi, $total_biaya]);

    $pdo->commit();
    header('Location: bayar.php');
    exit;

} catch (\PDOException $e) {
    $pdo->rollBack();
    header('Location: checkout.php?error=' . urlencode('Gagal memproses: ' . $e->getMessage()));
    exit;
}
