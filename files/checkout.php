<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kendaraan Keluar</title>
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
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Proses Kendaraan Keluar</h1>

        <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-lg overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-800/50">
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Plat Nomor</th>
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Pemilik</th>
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Jenis</th>
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Slot</th>
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Waktu Masuk</th>
                        <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT t.id_transaksi, k.plat_nomor, p.nama, j.nama_jenis,
                                   sp.kode_slot, t.waktu_masuk
                            FROM transaksi_parkir t
                            JOIN kendaraan k ON k.id_kendaraan = t.id_kendaraan
                            JOIN pemilik p ON p.id_pemilik = k.id_pemilik
                            JOIN jenis_kendaraan j ON j.id_jenis = k.id_jenis
                            JOIN slot_parkir sp ON sp.id_slot = t.id_slot
                            WHERE t.status = 'parkir'
                            ORDER BY t.waktu_masuk";
                    $data = $pdo->query($sql)->fetchAll();
                    if (count($data) === 0): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">Tidak ada kendaraan yang sedang parkir</td>
                        </tr>
                    <?php else:
                        foreach ($data as $d): ?>
                        <tr class="border-b border-gray-200 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800/50">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 font-medium"><?= htmlspecialchars($d['plat_nomor']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= htmlspecialchars($d['nama']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= htmlspecialchars($d['nama_jenis']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= htmlspecialchars($d['kode_slot']) ?></td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 font-mono"><?= htmlspecialchars($d['waktu_masuk']) ?></td>
                            <td class="px-4 py-3 text-sm">
                                <form method="post" action="proses_keluar.php" class="inline">
                                    <input type="hidden" name="id_transaksi" value="<?= $d['id_transaksi'] ?>">
                                    <button type="submit" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors">
                                        Keluar Sekarang
                                    </button>
                                </form>
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
