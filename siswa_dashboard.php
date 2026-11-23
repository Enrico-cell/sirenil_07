<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
    // Jika login sebagai admin/guru atau belum login, sesuaikan atau arahkan ke login
    if(!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }
}

$id_user = $_SESSION['id_user'];
$data_nilai = mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_user='$id_user'");
$data_absensi = mysqli_query($koneksi, "SELECT * FROM absensi WHERE id_user='$id_user' ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Siswa - SIRENIL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { background-color: #F5F0E6; color: #1E3B2F; }</style>
</head>
<body class="p-4 md:p-8">
    <div class="max-w-4xl mx-auto">
        <?php include 'navbar.php'; ?>
        <div class="flex justify-between items-center mb-6 bg-white p-4 rounded shadow">
            <div>
                <h1 class="text-xl font-bold">Portal Nilai & Absensi Siswa</h1>
                <p class="text-sm text-gray-600">Halo, <?= htmlspecialchars($_SESSION['nama']) ?></p>
            </div>
            <a href="logout.php" class="px-4 py-2 bg-gray-500 text-white rounded text-sm">Logout</a>
        </div>

        <div class="space-y-6">
            <!-- Rekap Nilai -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-bold text-lg mb-4" style="color: #1E3B2F;">Daftar Nilai Anda</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="text-white" style="background-color: #1E3B2F;">
                                <th class="p-2">Mata Pelajaran</th>
                                <th class="p-2">Nilai</th>
                                <th class="p-2">Semester</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <?php if(mysqli_num_rows($data_nilai) > 0): ?>
                                <?php while($n = mysqli_fetch_assoc($data_nilai)): ?>
                                <tr>
                                    <td class="p-2"><?= htmlspecialchars($n['mata_pelajaran']) ?></td>
                                    <td class="p-2 font-bold"><?= htmlspecialchars($n['nilai']) ?></td>
                                    <td class="p-2"><?= htmlspecialchars($n['semester']) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="p-3 text-center text-gray-500">Belum ada data nilai.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Rekap Absensi -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="font-bold text-lg mb-4" style="color: #1E3B2F;">Riwayat Absensi Anda</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="text-white" style="background-color: #7F8C6A;">
                                <th class="p-2">Tanggal</th>
                                <th class="p-2">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <?php if(mysqli_num_rows($data_absensi) > 0): ?>
                                <?php while($a = mysqli_fetch_assoc($data_absensi)): ?>
                                <tr>
                                    <td class="p-2"><?= htmlspecialchars($a['tanggal']) ?></td>
                                    <td class="p-2 font-semibold"><?= htmlspecialchars($a['keterangan']) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="p-3 text-center text-gray-500">Belum ada data absensi.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>