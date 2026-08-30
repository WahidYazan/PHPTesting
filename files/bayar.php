<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        slate: {
                            850: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans dark:bg-slate-950 dark:text-gray-100 transition-colors duration-200">

<script>
// Apply theme immediately to prevent flash
(function() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark');
    }
})();
</script>

    <?php include 'navbar.php'; ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Belum Bayar</h1>

        <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-lg overflow-hidden mb-8">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-800/50">
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Plat Nomor</th>
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Pemilik</th>
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Waktu Masuk</th>
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Waktu Keluar</th>
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Biaya</th>
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT pb.id_pembayaran, pb.jumlah_bayar, k.plat_nomor, p.nama,
                                   t.waktu_masuk, t.waktu_keluar
                            FROM pembayaran pb
                            JOIN transaksi_parkir t ON t.id_transaksi = pb.id_transaksi
                            JOIN kendaraan k ON k.id_kendaraan = t.id_kendaraan
                            JOIN pemilik p ON p.id_pemilik = k.id_pemilik
                            WHERE pb.status = 'belum_lunas'
                            ORDER BY t.waktu_keluar";
                    $belum = $pdo->query($sql)->fetchAll();
                    if (count($belum) === 0): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">Tidak ada tagihan yang belum dibayar</td>
                        </tr>
                    <?php else:
                        foreach ($belum as $b): ?>
                        <tr class="border-b border-gray-200 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800/50">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 font-medium"><?= htmlspecialchars($b['plat_nomor']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= htmlspecialchars($b['nama']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 font-mono"><?= htmlspecialchars($b['waktu_masuk']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 font-mono"><?= htmlspecialchars($b['waktu_keluar']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 font-medium">Rp <?= number_format($b['jumlah_bayar'], 0, ',', '.') ?></td>
                            <td class="px-4 py-3 text-sm">
                                <form method="post" action="proses_bayar.php" class="inline">
                                    <input type="hidden" name="id_pembayaran" value="<?= $b['id_pembayaran'] ?>">
                                    <select name="metode_pembayaran" class="text-gray-900 dark:text-gray-100 w-auto inline-block px-2 py-1 mr-2 border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="cash">Cash</option>
                                        <option value="qris">QRIS</option>
                                        <option value="debit">Debit</option>
                                    </select>
                                    <button type="submit" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition-colors">
                                        Tandai Lunas
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>

        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-8 mb-4">Riwayat Lunas</h2>

        <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-lg overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-800/50">
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Plat Nomor</th>
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Pemilik</th>
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Waktu Keluar</th>
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Biaya</th>
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Metode</th>
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql2 = "SELECT pb.jumlah_bayar, pb.metode_pembayaran, k.plat_nomor, p.nama, t.waktu_keluar
                             FROM pembayaran pb
                             JOIN transaksi_parkir t ON t.id_transaksi = pb.id_transaksi
                             JOIN kendaraan k ON k.id_kendaraan = t.id_kendaraan
                             JOIN pemilik p ON p.id_pemilik = k.id_pemilik
                             WHERE pb.status = 'lunas'
                             ORDER BY t.waktu_keluar DESC
                             LIMIT 20";
                    $lunas = $pdo->query($sql2)->fetchAll();
                    if (count($lunas) === 0): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">Belum ada riwayat pembayaran lunas</td>
                        </tr>
                    <?php else:
                        foreach ($lunas as $l): ?>
                        <tr class="border-b border-gray-200 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800/50">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 font-medium"><?= htmlspecialchars($l['plat_nomor']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= htmlspecialchars($l['nama']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 font-mono"><?= htmlspecialchars($l['waktu_keluar']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 font-medium">Rp <?= number_format($l['jumlah_bayar'], 0, ',', '.') ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 uppercase"><?= htmlspecialchars(strtoupper($l['metode_pembayaran'])) ?></td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    Lunas
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>

        </div>
    </div>
</body>
</html>
