<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'guru')) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['tambah_siswa'])) {
    $nama      = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $nis       = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $kelas     = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $jurusan   = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $jk        = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);

    mysqli_query($koneksi, "INSERT INTO user (role, nis, nama, nama_user, kelas, jurusan, jenis_kelamin) VALUES ('siswa', '$nis', '$nama', '$nama', '$kelas', '$jurusan', '$jk')");
    header("Location: admin_siswa.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM user WHERE id_user='$id'");
    header("Location: admin_siswa.php");
    exit;
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$filter_kelas = isset($_GET['kelas']) ? mysqli_real_escape_string($koneksi, $_GET['kelas']) : '';

$query_str = "SELECT * FROM user WHERE role='siswa'";
if (!empty($search)) {
    $query_str .= " AND (nama LIKE '%$search%' OR nis LIKE '%$search%')";
}
if (!empty($filter_kelas)) {
    $query_str .= " AND kelas = '$filter_kelas'";
}
$query_str .= " ORDER BY nama ASC";

$data_siswa = mysqli_query($koneksi, $query_str);
$list_kelas = mysqli_query($koneksi, "SELECT DISTINCT kelas FROM user WHERE role='siswa' AND kelas IS NOT NULL AND kelas != ''");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Siswa - SIRENIL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #F5F0E6; color: #1E3B2F; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow: hidden; }
    </style>
</head>
<body class="p-3 md:p-5 h-screen flex flex-col justify-between">
    <div class="max-w-6xl mx-auto w-full flex flex-col h-full">
        <!-- Navigasi Terpusat -->
        <?php include 'navbar.php'; ?>

        <!-- Header Judul Estetik -->
        <div class="mb-4 bg-white px-5 py-3.5 rounded-xl shadow-sm border border-emerald-900/10 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-black tracking-wide" style="color: #1E3B2F;">Kelola Data Siswa</h1>
                <p class="text-[11px] text-gray-500">Pusat pengelolaan biodata siswa, pencarian cepat, dan cetak laporan per kelas.</p>
            </div>
            <span class="px-3 py-1 text-xs font-bold rounded-lg border" style="background-color: #1E3B2F; color: #F5F0E6; border-color: #1E3B2F;">
                Database Siswa
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start pb-4">
            <!-- Form Tambah Siswa dengan Scroll Internal -->
            <div class="bg-white p-5 rounded-2xl shadow-md border-t-4 max-h-[calc(100vh-210px)] overflow-y-auto" style="border-color: #1E3B2F;">
                <h3 class="font-bold text-sm mb-3 flex items-center gap-2" style="color: #1E3B2F;">
                    <svg class="w-4 h-4 text-emerald-800" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Siswa Baru
                </h3>
                <form method="POST" class="space-y-2.5 text-xs">
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Nama Lengkap</label>
                        <input type="text" name="nama" required placeholder="Nama lengkap siswa..." class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">NIS</label>
                        <input type="text" name="nis" required placeholder="Nomor Induk Siswa..." class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Kelas</label>
                        <input type="text" name="kelas" placeholder="Contoh: XI RPL 1" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Jurusan</label>
                        <input type="text" name="jurusan" placeholder="Contoh: RPL" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50 font-medium">
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="pt-1">
                        <button type="submit" name="tambah_siswa" class="w-full text-white py-2.5 rounded-lg font-bold transition shadow-md hover:opacity-95 text-xs tracking-wide" style="background-color: #1E3B2F;">
                            Simpan Data Siswa
                        </button>
                    </div>
                </form>
            </div>

            <!-- Bagian Tabel & Filter -->
            <div class="lg:col-span-2 space-y-3.5 max-h-[calc(100vh-210px)] flex flex-col">
                <!-- Bar Filter & Cetak Laporan -->
                <div class="bg-white p-3.5 rounded-2xl shadow-sm border border-gray-100 flex flex-wrap justify-between items-center gap-2">
                    <form method="GET" class="flex flex-wrap gap-2 w-full md:w-auto">
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari Nama / NIS..." class="px-3 py-1.5 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#7F8C6A]">
                        <select name="kelas" class="px-3 py-1.5 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] font-medium">
                            <option value="">-- Semua Kelas --</option>
                            <?php while($k = mysqli_fetch_assoc($list_kelas)): ?>
                                <option value="<?= htmlspecialchars($k['kelas']) ?>" <?= $filter_kelas == $k['kelas'] ? 'selected' : '' ?>><?= htmlspecialchars($k['kelas']) ?></option>
                            <?php endwhile; ?>
                        </select>
                        <button type="submit" class="px-3.5 py-1.5 text-white rounded-xl text-xs font-semibold shadow-sm transition hover:opacity-90" style="background-color: #7F8C6A;">Filter</button>
                    </form>
                    <a href="cetak_laporan_siswa.php?kelas=<?= urlencode($filter_kelas) ?>" target="_blank" class="px-3.5 py-1.5 text-white rounded-xl font-semibold text-xs shadow-sm transition hover:opacity-90" style="background-color: #1E3B2F;">
                        🖨️ Cetak Laporan Per Kelas
                    </a>
                </div>

                <!-- Tabel Data Siswa -->
                <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100 flex-1 flex flex-col">
                    <div class="overflow-y-auto flex-1">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead class="sticky top-0 text-white uppercase tracking-wider z-10" style="background-color: #1E3B2F;">
                                <tr>
                                    <th class="p-3 pl-4">NIS</th>
                                    <th class="p-3">Nama Siswa</th>
                                    <th class="p-3">Kelas / Jurusan</th>
                                    <th class="p-3">JK</th>
                                    <th class="p-3 text-center pr-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if(mysqli_num_rows($data_siswa) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($data_siswa)): ?>
                                    <tr class="hover:bg-emerald-50/30 transition duration-150">
                                        <td class="p-3 pl-4 font-mono text-[11px] text-gray-600"><?= htmlspecialchars($row['nis'] ?? '-') ?></td>
                                        <td class="p-3 font-semibold text-gray-800"><?= htmlspecialchars($row['nama'] ?? $row['nama_user']) ?></td>
                                        <td class="p-3 text-gray-600 font-medium"><?= htmlspecialchars(($row['kelas'] ?? '').' '.($row['jurusan'] ?? '')) ?></td>
                                        <td class="p-3 text-gray-600"><?= htmlspecialchars($row['jenis_kelamin'] ?? '-') ?></td>
                                        <td class="p-3 text-center pr-4">
                                            <a href="edit_siswa.php?id=<?= (int)$row['id_user'] ?>" class="inline-block px-2 py-1 mr-1 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-700 hover:text-white transition font-bold shadow-sm">Edit</a>
                                            <a href="?hapus=<?= $row['id_user'] ?>" onclick="return confirm('Hapus siswa ini?')" class="px-2 py-1 rounded-md bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition font-bold shadow-sm">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="p-6 text-center text-gray-400">Tidak ada data siswa ditemukan.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>