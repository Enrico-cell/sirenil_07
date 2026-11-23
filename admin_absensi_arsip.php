<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'guru')) {
    header("Location: login.php");
    exit;
}

$filter_bulan = isset($_GET['bulan']) ? mysqli_real_escape_string($koneksi, $_GET['bulan']) : '';
$query_str = "SELECT * FROM absensi_arsip";
if (!empty($filter_bulan)) {
    $query_str .= " WHERE bulan = '$filter_bulan'";
}
$query_str .= " ORDER BY id_arsip DESC";

$data_arsip = mysqli_query($koneksi, $query_str);
$list_bulan = mysqli_query($koneksi, "SELECT DISTINCT bulan FROM absensi_arsip WHERE bulan IS NOT NULL AND bulan != '' ORDER BY bulan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Arsip Absensi - SIRENIL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #F5F0E6; color: #1E3B2F; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow: hidden; }
    </style>
</head>
<body class="p-3 md:p-5 h-screen flex flex-col justify-between">
    <div class="max-w-6xl mx-auto w-full flex flex-col h-full">
        <!-- Navigasi Terpusat -->
        <?php include 'navbar.php'; ?>

        <!-- Header Judul Serasi -->
        <div class="mb-4 bg-white px-5 py-3.5 rounded-xl shadow-sm border border-emerald-900/10 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-black tracking-wide" style="color: #1E3B2F;">Arsip Absensi Bulanan</h1>
                <p class="text-[11px] text-gray-500">Pusat rekapitulasi riwayat kehadiran dan arsip laporan absensi siswa per periode.</p>
            </div>
            <a href="admin_dashboard.php" class="px-4 py-1.5 rounded-xl text-xs font-bold transition shadow-sm" style="background-color: #1E3B2F; color: #F5F0E6;">
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100 flex-1 flex flex-col max-h-[calc(100vh-160px)]">
            <!-- Bar Filter Arsip -->
            <div class="p-4 border-b border-gray-100 flex flex-wrap justify-between items-center bg-gray-50/50 gap-2">
                <form method="GET" class="flex items-center gap-2">
                    <select name="bulan" class="px-3 py-1.5 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] font-medium">
                        <option value="">-- Semua Bulan Arsip --</option>
                        <?php while($b = mysqli_fetch_assoc($list_bulan)): ?>
                            <option value="<?= htmlspecialchars($b['bulan']) ?>" <?= $filter_bulan == $b['bulan'] ? 'selected' : '' ?>><?= htmlspecialchars($b['bulan']) ?></option>
                        <?php endwhile; ?>
                    </select>
                    <button type="submit" class="px-3.5 py-1.5 text-white rounded-xl text-xs font-semibold shadow-sm transition hover:opacity-90" style="background-color: #7F8C6A;">Filter Arsip</button>
                </form>
                <span class="text-[11px] text-gray-400 font-medium">Data Riwayat Tersimpan</span>
            </div>

            <!-- Tabel Arsip Absensi -->
            <div class="overflow-y-auto flex-1">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="sticky top-0 text-white uppercase tracking-wider z-10" style="background-color: #1E3B2F;">
                        <tr>
                            <th class="p-3 pl-4">Periode Bulan</th>
                            <th class="p-3">Keterangan / Detail</th>
                            <th class="p-3 text-center">Tanggal Arsip</th>
                            <th class="p-3 text-center pr-4">Aksi / Unduh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if($data_arsip && mysqli_num_rows($data_arsip) > 0): ?>
                            <?php while($arsip = mysqli_fetch_assoc($data_arsip)): ?>
                            <tr class="hover:bg-emerald-50/30 transition duration-150">
                                <td class="p-3 pl-4 font-semibold text-gray-800"><?= htmlspecialchars($arsip['bulan'] ?? '-') ?></td>
                                <td class="p-3 text-gray-700"><?= htmlspecialchars($arsip['keterangan'] ?? 'Arsip Absensi Siswa') ?></td>
                                <td class="p-3 text-center text-gray-600 font-mono text-[11px]"><?= htmlspecialchars($arsip['tanggal_arsip'] ?? '-') ?></td>
                                <td class="p-3 text-center pr-4">
                                    <a href="edit_arsip.php?id=<?= (int)$arsip['id_arsip'] ?>" class="inline-block px-2 py-1 mr-1 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-700 hover:text-white transition font-bold shadow-sm">Edit</a>
                                    <a href="cetak_arsip.php?id=<?= $arsip['id_arsip'] ?>" target="_blank" class="px-3 py-1 rounded-md text-white text-[11px] font-bold shadow-sm transition hover:opacity-90" style="background-color: #1E3B2F;">
                                        📂 Lihat / Cetak
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="p-8 text-center text-gray-400">Belum ada arsip absensi bulanan yang tersimpan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>