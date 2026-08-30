<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kendaraan Masuk</title>
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

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Catat Kendaraan Masuk</h1>

        <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-900 dark:text-red-300 p-4 rounded-lg mb-6 text-sm">
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
        <?php endif; ?>

        <form class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-lg p-6" method="post" action="simpan_transaksi.php">

            <label class="block mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Metode</label>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <label class="relative border-2 border-blue-500 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 cursor-pointer">
                    <input type="radio" name="mode" value="pilih" checked onchange="toggleMode()" class="sr-only">
                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Kendaraan Terdaftar</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pilih kendaraan yang sudah tersimpan</div>
                </label>

                <label class="relative border-2 border-gray-200 dark:border-slate-700 rounded-lg p-4 cursor-pointer hover:border-gray-300 dark:hover:border-slate-600">
                    <input type="radio" name="mode" value="baru" onchange="toggleMode()" class="sr-only">
                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Kendaraan Baru</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Masukkan kendaraan baru</div>
                </label>
            </div>

            <div id="mode-pilih">
                <label class="block mt-6 mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Kendaraan (Plat Nomor)</label>
                <select name="id_kendaraan" id="id_kendaraan" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 rounded-md text-sm bg-white dark:bg-slate-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih Kendaraan Yang Tersedia</option>
                    <?php
            $sql = "SELECT k.id_kendaraan, k.plat_nomor, p.nama
                    FROM kendaraan k 
                    JOIN pemilik p ON p.id_pemilik = k.id_pemilik
                    WHERE k.id_kendaraan NOT IN (
                        SELECT id_kendaraan FROM transaksi_parkir WHERE status = 'parkir'
                    )
                    ORDER BY k.plat_nomor";
            foreach ($pdo->query($sql) as $k): ?>
                    <option value="<?= $k['id_kendaraan'] ?>">
                        <?= htmlspecialchars($k['plat_nomor']) ?> - <?= htmlspecialchars($k['nama']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="mode-baru" style="display: none;">
                <label class="block mt-6 mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Plat Nomor Baru</label>
                <input type="text" name="plat_nomor_baru" id="plat_nomor_baru" placeholder="Contoh: B 1234 ABC" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 rounded-md text-sm bg-white dark:bg-slate-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                <label class="block mt-4 mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nama Pemilik</label>
                <input type="text" name="nama_pemilik" id="nama_pemilik" placeholder="Nama pemilik kendaraan" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 rounded-md text-sm bg-white dark:bg-slate-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                <label class="block mt-4 mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Kendaraan</label>
                <select name="id_jenis" id="id_jenis" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 rounded-md text-sm bg-white dark:bg-slate-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih Jenis Kendaraan</option>
                    <?php
            $sql_jenis = "SELECT id_jenis, nama_jenis FROM jenis_kendaraan ORDER BY nama_jenis";
            foreach ($pdo->query($sql_jenis) as $j): ?>
                    <option value="<?= $j['id_jenis'] ?>">
                        <?= htmlspecialchars($j['nama_jenis']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <script>
                function toggleMode() {
                    const mode = document.querySelector('input[name="mode"]:checked').value;
                    const modePilih = document.getElementById('mode-pilih');
                    const modeBaru = document.getElementById('mode-baru');
                    const idKendaraan = document.getElementById('id_kendaraan');
                    const platNomorBaru = document.getElementById('plat_nomor_baru');
                    const namaPemilik = document.getElementById('nama_pemilik');
                    const idJenis = document.getElementById('id_jenis');
                    const cards = document.querySelectorAll('label');

                    if (mode === 'pilih') {
                        modePilih.style.display = 'block';
                        modeBaru.style.display = 'none';
                        idKendaraan.required = true;
                        platNomorBaru.required = false;
                        namaPemilik.required = false;
                        idJenis.required = false;
                        
                        cards[0].classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                        cards[0].classList.remove('border-gray-200', 'dark:border-slate-700');
                        cards[1].classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                        cards[1].classList.add('border-gray-200', 'dark:border-slate-700');
                    } else {
                        modePilih.style.display = 'none';
                        modeBaru.style.display = 'block';
                        idKendaraan.required = false;
                        platNomorBaru.required = true;
                        namaPemilik.required = true;
                        idJenis.required = true;
                        
                        cards[0].classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                        cards[0].classList.add('border-gray-200', 'dark:border-slate-700');
                        cards[1].classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                        cards[1].classList.remove('border-gray-200', 'dark:border-slate-700');
                    }
                }
            </script>

            <label class="block mt-6 mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Slot Parkir Kosong</label>
            <select name="id_slot" required class="w-full px-3 py-2 border border-gray-300 dark:border-slate-700 rounded-md text-sm bg-white dark:bg-slate-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Pilih Area</option>
                <?php
        $sql = "SELECT id_slot, kode_slot, lokasi FROM slot_parkir WHERE status = 'tersedia' ORDER BY kode_slot";
        $kosong = $pdo->query($sql)->fetchAll();
        if (count($kosong) === 0): ?>
                <option value="" disabled>Tidak ada slot kosong</option>
                <?php else:
            foreach ($kosong as $s): ?>
                <option value="<?= $s['id_slot'] ?>">
                    <?= htmlspecialchars($s['kode_slot']) ?> - <?= htmlspecialchars($s['lokasi']) ?>
                </option>
                <?php endforeach; endif; ?>
            </select>

            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                Waktu masuk akan otomatis dicatat sesuai waktu server saat form disimpan.
            </p>

            <div class="mt-6 flex gap-3">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                    Simpan
                </button>
                <a href="index.php" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-900 dark:text-gray-100 text-sm font-medium rounded-md transition-colors no-underline">
                    Batal
                </a>
            </div>
        </form>

        </div>
    </div>
</body>
</html>