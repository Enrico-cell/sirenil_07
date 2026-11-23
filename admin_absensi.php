<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'guru')) {
    header("Location: login.php");
    exit;
}

// Proses Simpan Absensi
if (isset($_POST['simpan_absensi'])) {
    $id_siswa   = mysqli_real_escape_string($koneksi, $_POST['id_user']);
    $tanggal    = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $status     = mysqli_real_escape_string($koneksi, $_POST['status']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    mysqli_query($koneksi, "INSERT INTO absensi (id_user, tanggal, status, keterangan) VALUES ('$id_siswa', '$tanggal', '$status', '$keterangan')");
    header("Location: admin_absensi.php");
    exit;
}

// Proses Hapus Absensi
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM absensi WHERE id_absensi='$id'");
    header("Location: admin_absensi.php");
    exit;
}

$data_siswa  = mysqli_query($koneksi, "SELECT * FROM user WHERE role='siswa' ORDER BY nama ASC");
$data_absensi = mysqli_query($koneksi, "SELECT absensi.*, user.nama, user.nama_user, user.nis, user.kelas FROM absensi JOIN user ON absensi.id_user = user.id_user ORDER BY absensi.tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Absensi Siswa - SIRENIL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #F5F0E6; color: #1E3B2F; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow: hidden; }
    </style>
</head>
<body class="p-3 md:p-5 h-screen flex flex-col justify-between">
    <div class="max-w-6xl mx-auto w-full flex flex-col h-full">
        <!-- Navigasi Terpusat -->
        <?php include 'navbar.php'; ?>

        <!-- Header Atas Persis Seperti Permintaan (Kotak Putih Bersih + Tombol Hijau Tua) -->
        <div class="mb-4 bg-white px-5 py-3.5 rounded-xl shadow-sm border border-emerald-900/10 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-black tracking-wide" style="color: #1E3B2F;">Kelola Absensi Siswa</h1>
                <p class="text-[11px] text-gray-500">Pusat pemantauan kehadiran, pencatatan harian, dan rekap status absensi siswa.</p>
            </div>
            <a href="admin_dashboard.php" class="px-4 py-1.5 rounded-xl text-xs font-bold transition shadow-sm" style="background-color: #1E3B2F; color: #F5F0E6;">
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start pb-4">
            <!-- Form Catat Absensi dengan Scroll Internal -->
            <div class="bg-white p-5 rounded-2xl shadow-md border-t-4 max-h-[calc(100vh-210px)] overflow-y-auto" style="border-color: #1E3B2F;">
                <h3 class="font-bold text-sm mb-3 flex items-center gap-2" style="color: #1E3B2F;">
                    <svg class="w-4 h-4 text-emerald-800" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Catat Kehadiran Baru
                </h3>
                <form method="POST" class="space-y-2.5 text-xs">
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Pilih Siswa</label>
                        <select name="id_user" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50 font-medium">
                            <option value="">-- Pilih Siswa --</option>
                            <?php while($s = mysqli_fetch_assoc($data_siswa)): ?>
                                <option value="<?= $s['id_user'] ?>"><?= htmlspecialchars($s['nama'] ?? $s['nama_user']) ?> (<?= htmlspecialchars($s['kelas'] ?? '-') ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Tanggal</label>
                        <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Status Kehadiran</label>
                        <select name="status" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50 font-medium">
                            <option value="Hadir">Hadir</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Alfa">Alfa</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Keterangan (Opsional)</label>
                        <input type="text" name="keterangan" placeholder="Contoh: Terlambat, Dispensasi..." class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50">
                    </div>
                    <div class="pt-1">
                        <button type="submit" name="simpan_absensi" class="w-full text-white py-2.5 rounded-lg font-bold transition shadow-md hover:opacity-95 text-xs tracking-wide" style="background-color: #1E3B2F;">
                            Simpan Absensi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Data Rekapitulasi Absensi -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100 max-h-[calc(100vh-210px)] flex flex-col">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-xs text-gray-700 uppercase tracking-wider">Riwayat Kehadiran Siswa</h3>
                    <span class="text-[11px] text-gray-400 font-medium">Data Absensi</span>
                </div>
                <div class="overflow-y-auto flex-1">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="sticky top-0 text-white uppercase tracking-wider z-10" style="background-color: #1E3B2F;">
                            <tr>
                                <th class="p-3 pl-4">NIS & Nama</th>
                                <th class="p-3">Tanggal</th>
                                <th class="p-3 text-center">Status</th>
                                <th class="p-3">Keterangan</th>
                                <th class="p-3 text-center pr-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if($data_absensi && mysqli_num_rows($data_absensi) > 0): ?>
                                <?php while($a = mysqli_fetch_assoc($data_absensi)): ?>
                                <tr class="hover:bg-emerald-50/30 transition duration-150">
                                    <td class="p-3 pl-4 font-semibold text-gray-800">
                                        <?= htmlspecialchars($a['nama'] ?? $a['nama_user']) ?>
                                        <div class="text-[10px] text-gray-400 font-mono"><?= htmlspecialchars($a['nis'] ?? '-') ?> • <?= htmlspecialchars($a['kelas'] ?? '-') ?></div>
                                    </td>
                                    <td class="p-3 text-gray-600 font-mono text-[11px]"><?= htmlspecialchars($a['tanggal']) ?></td>
                                    <td class="p-3 text-center">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold tracking-wide shadow-sm 
                                            <?= $a['status']=='Hadir' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($a['status']=='Sakit' ? 'bg-blue-100 text-blue-700 border border-blue-200' : ($a['status']=='Izin' ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-red-100 text-red-700 border border-red-200')) ?>">
                                            <?= strtoupper($a['status']) ?>
                                        </span>
                                    </td>
                                    <td class="p-3 text-gray-600"><?= htmlspecialchars($a['keterangan'] ?: '-') ?></td>
                                    <td class="p-3 text-center pr-4">
                                        <a href="edit_absensi.php?id=<?= (int)$a['id_absensi'] ?>" class="inline-block px-2 py-1 mr-1 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-700 hover:text-white transition font-bold shadow-sm">Edit</a>
                                        <a href="?hapus=<?= $a['id_absensi'] ?>" onclick="return confirm('Hapus data absensi ini?')" class="px-2 py-1 rounded-md bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition font-bold shadow-sm">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="p-8 text-center text-gray-400">Belum ada data absensi tercatat.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>