<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Parkir</title>
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

<?php
// Hitung status slot
$total  = $pdo->query("SELECT COUNT(*) FROM slot_parkir")->fetchColumn();
$terisi = $pdo->query("SELECT COUNT(*) FROM slot_parkir WHERE status = 'tersedia'")->fetchColumn();
$kosong = $total - $terisi;
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-lg p-6">
        <div class="text-3xl font-semibold text-gray-900 dark:text-gray-100 mb-1"><?= $total ?></div>
        <div class="text-sm text-gray-500 dark:text-gray-400">Total Slot</div>
    </div>
    <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-lg p-6">
        <div class="text-3xl font-semibold text-gray-900 dark:text-gray-100 mb-1"><?= $terisi ?></div>
        <div class="text-sm text-gray-500 dark:text-gray-400">Slot Tersedia</div>
    </div>
    <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-lg p-6">
        <div class="text-3xl font-semibold text-gray-900 dark:text-gray-100 mb-1"><?= $kosong ?></div>
        <div class="text-sm text-gray-500 dark:text-gray-400">Slot Terisi</div>
    </div>
</div>

<h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Status Slot Yang Terisi</h2>
<div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-lg overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-800/50">
                <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Slot</th>
                <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Lokasi</th>
                <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $slots = $pdo->query("SELECT * FROM slot_parkir WHERE status = 'terisi' ORDER BY kode_slot")->fetchAll();
            if (count($slots) === 0): ?>
                <tr>
                    <td colspan="3" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">Tidak ada slot yang terisi</td>
                </tr>
            <?php else:
                foreach ($slots as $s): ?>
                <tr class="border-b border-gray-200 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800/50">
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100"><?= htmlspecialchars($s['kode_slot']) ?></td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= htmlspecialchars($s['lokasi']) ?></td>
                    <td class="px-4 py-3 text-sm">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                            Terisi
                        </span>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 mt-10">Status Slot Yang Tersedia</h2>
<div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-lg overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-800/50">
                <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Slot</th>
                <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Lokasi</th>
                <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $slots = $pdo->query("SELECT * FROM slot_parkir WHERE status = 'tersedia' ORDER BY kode_slot")->fetchAll();
            if (count($slots) === 0): ?>
                <tr>
                    <td colspan="3" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">Belum ada data slot parkir</td>
                </tr>
            <?php else:
                foreach ($slots as $s): ?>
                <tr class="border-b border-gray-200 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800/50">
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100"><?= htmlspecialchars($s['kode_slot']) ?></td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= htmlspecialchars($s['lokasi']) ?></td>
                    <td class="px-4 py-3 text-sm">
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            Tersedia
                        </span>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-8 mb-4">Kendaraan Sedang Parkir</h2>
<div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-lg overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-800/50">
                <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Plat Nomor</th>
                <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Pemilik</th>
                <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Jenis</th>
                <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Lokasi / Slot</th>
                <th class="text-left px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300">Waktu Masuk</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT t.id_transaksi, k.plat_nomor, p.nama, j.nama_jenis,
                           sp.kode_slot, sp.lokasi, t.waktu_masuk
                    FROM transaksi_parkir t
                    JOIN kendaraan k ON k.id_kendaraan = t.id_kendaraan
                    JOIN pemilik p ON p.id_pemilik = k.id_pemilik
                    JOIN jenis_kendaraan j ON j.id_jenis = k.id_jenis
                    JOIN slot_parkir sp ON sp.id_slot = t.id_slot
                    WHERE t.status = 'parkir'
                    ORDER BY t.waktu_masuk DESC";
            $aktif = $pdo->query($sql)->fetchAll();
            if (count($aktif) === 0): ?>
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">Tidak ada kendaraan yang sedang parkir</td>
                </tr>
            <?php else:
                foreach ($aktif as $a): ?>
                <tr class="border-b border-gray-200 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800/50">
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 font-medium"><?= htmlspecialchars($a['plat_nomor']) ?></td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= htmlspecialchars($a['nama']) ?></td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= htmlspecialchars($a['nama_jenis']) ?></td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300"><?= htmlspecialchars($a['kode_slot']) ?> - <?= htmlspecialchars($a['lokasi']) ?></td>
                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 font-mono"><?= htmlspecialchars($a['waktu_masuk']) ?></td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

    </div>
</div>
</body>
</html>
