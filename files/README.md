# Sistem Parkir - Web App (PHP + MariaDB Servbay)

## Cara Menjalankan
1. Copy folder `parkir_app` ini ke folder web root Servbay Anda
   (biasanya sesuatu seperti `~/ServBay/www/` atau yang sudah Anda set di Servbay).
2. Buka `config.php`, pastikan host/port/user/password sudah sesuai
   (sudah diisi default: host `localhost`, port `3306`, user `root`, password `ServBay.dev`).
3. Buka browser ke `http://localhost/parkir_app/index.php`
   (sesuaikan domain/port sesuai konfigurasi Servbay Anda).

## Struktur Database (sudah disesuaikan dengan tabel Anda)
Kode sudah disesuaikan persis dengan `CREATE TABLE` yang Anda berikan:

- `jenis_kendaraan`: id_jenis, nama_jenis
- `pemilik`: id_pemilik, nama, no_hp, alamat
- `kendaraan`: id_kendaraan, id_pemilik, id_jenis, plat_nomor, merk, warna
- `slot_parkir`: id_slot, kode_slot, lokasi, status ('kosong'/'terisi')
- `tarif`: id_tarif, id_jenis, tarif_per_jam
- `transaksi_parkir`: id_transaksi, id_kendaraan, id_slot, waktu_masuk, waktu_keluar, total_biaya, status ('parkir'/'selesai')
- `pembayaran`: id_pembayaran, id_transaksi, waktu_pembayaran, jumlah_bayar, metode_pembayaran ('cash'/'qris'/'debit'), status ('lunas'/'belum_lunas')

**Catatan penting:**
- Sebelum aplikasi bisa dipakai, pastikan tabel `pemilik`, `kendaraan`, `slot_parkir`, dan `tarif` sudah punya data (minimal 1 pemilik, 1 kendaraan, beberapa slot, dan tarif per jenis kendaraan) — karena form "+ Kendaraan Masuk" mengambil datanya dari situ.
- Perhitungan biaya: `total_biaya = jam_parkir × tarif_per_jam` (durasi dibulatkan ke atas per jam, minimal 1 jam).

| File                     | Fungsi                                          |
|--------------------------|--------------------------------------------------|
| `index.php`              | Dashboard: jumlah slot, status, transaksi aktif  |
| `tambah_transaksi.php`   | Form catat kendaraan masuk                      |
| `simpan_transaksi.php`   | Proses simpan transaksi masuk                   |
| `checkout.php`           | Daftar kendaraan yang bisa diproses keluar      |
| `proses_keluar.php`      | Hitung biaya, catat waktu keluar                |
| `bayar.php`              | Daftar tagihan belum/sudah lunas                |
| `proses_bayar.php`       | Tandai pembayaran lunas                         |

## Alur Pemakaian
1. **Dashboard** (`index.php`) — lihat jumlah slot kosong/terisi & kendaraan yang sedang parkir.
2. **+ Kendaraan Masuk** — pilih kendaraan terdaftar & slot kosong, waktu masuk otomatis dicatat.
3. **Kendaraan Keluar** — klik "Keluar Sekarang", sistem otomatis menghitung biaya
   berdasarkan tarif per jenis kendaraan dan durasi parkir, lalu slot kembali kosong.
4. **Pembayaran** — tandai tagihan sebagai lunas.

## Kemungkinan Penyesuaian
- Jika kolom tarif Anda hanya punya satu kolom biaya (bukan tarif_awal + tarif_per_jam),
  sederhanakan perhitungan di `proses_keluar.php`.
- Jika kendaraan baru perlu didaftarkan langsung dari form (bukan pilih dari yang sudah ada),
  saya bisa tambahkan form pendaftaran pemilik & kendaraan baru — tinggal minta.
