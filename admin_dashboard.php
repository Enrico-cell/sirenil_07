<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'guru')) {
    header("Location: login.php");
    exit;
}

$total_user = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM user"));
$total_siswa = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM user WHERE role='siswa'"));
$total_nilai = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM nilai"));
$total_absensi = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM absensi"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - SIRENIL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { background-color: #F5F0E6; color: #1E3B2F; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }</style>
</head>
<body class="p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        <?php include 'navbar.php'; ?>

        <!-- Banner Sambutan Elegan -->
        <div class="mb-8 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center gap-4" style="background-color: #1E3B2F;">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 rounded-full opacity-10 bg-white"></div>
            <div>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold mb-3 tracking-wider uppercase" style="background-color: #7F8C6A; color: #F5F0E6;">
                    Panel <?= ucfirst($_SESSION['role']) ?>
                </span>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-wide mb-1">Selamat Datang, <?= htmlspecialchars($_SESSION['nama']) ?>!</h1>
                <p class="text-sm opacity-85 font-light">Sistem Informasi Rekapitulasi Nilai dan Pemantauan Kehadiran Absensi Siswa.</p>
            </div>
            <div class="bg-white/10 px-4 py-3 rounded-xl backdrop-blur-sm border border-white/10 text-right">
                <p class="text-xs opacity-70 uppercase tracking-wider font-semibold">Tanggal Hari Ini</p>
                <p class="text-sm font-bold mt-0.5"><?= date('d F Y') ?></p>
            </div>
        </div>

        <!-- Kartu Statistik Modern -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Total Pengguna -->
            <a href="admin_pengguna.php" class="bg-white p-6 rounded-2xl shadow-md border-l-4 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block" style="border-color: #1E3B2F;">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Total Pengguna</p>
                        <h3 class="text-3xl font-black" style="color: #1E3B2F;"><?= $total_user ?></h3>
                    </div>
                    <div class="p-3 rounded-xl bg-emerald-50 text-emerald-900 font-bold text-xs">Akun</div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center text-xs text-gray-500 font-medium">
                    <span>Kelola akses sistem</span>
                    <span class="ml-auto">&rarr;</span>
                </div>
            </a>

            <!-- Total Siswa -->
            <a href="admin_siswa.php" class="bg-white p-6 rounded-2xl shadow-md border-l-4 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block" style="border-color: #7F8C6A;">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Total Siswa</p>
                        <h3 class="text-3xl font-black" style="color: #1E3B2F;"><?= $total_siswa ?></h3>
                    </div>
                    <div class="p-3 rounded-xl bg-lime-50 text-lime-800 font-bold text-xs">Biodata</div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center text-xs text-gray-500 font-medium">
                    <span>Lihat data siswa</span>
                    <span class="ml-auto">&rarr;</span>
                </div>
            </a>

            <!-- Data Nilai -->
            <a href="admin_nilai.php" class="bg-white p-6 rounded-2xl shadow-md border-l-4 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block" style="border-color: #C8B48A;">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Data Nilai</p>
                        <h3 class="text-3xl font-black" style="color: #1E3B2F;"><?= $total_nilai ?></h3>
                    </div>
                    <div class="p-3 rounded-xl bg-amber-50 text-amber-800 font-bold text-xs">Akademik</div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center text-xs text-gray-500 font-medium">
                    <span>Rekapitulasi nilai</span>
                    <span class="ml-auto">&rarr;</span>
                </div>
            </a>

            <!-- Data Absensi -->
            <a href="admin_absensi.php" class="bg-white p-6 rounded-2xl shadow-md border-l-4 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 block" style="border-color: #1E3B2F;">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Data Absensi</p>
                        <h3 class="text-3xl font-black" style="color: #1E3B2F;"><?= $total_absensi ?></h3>
                    </div>
                    <div class="p-3 rounded-xl bg-emerald-50 text-emerald-900 font-bold text-xs">Kehadiran</div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center text-xs text-gray-500 font-medium">
                    <span>Absensi bulan ini</span>
                    <span class="ml-auto">&rarr;</span>
                </div>
            </a>
        </div>

        <!-- Bagian Informasi Tambahan / Pintasan -->
        <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100">
            <h2 class="text-lg font-bold mb-4" style="color: #1E3B2F;">Akses Cepat Menu Utama</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                <a href="admin_siswa.php" class="p-3 rounded-xl bg-gray-50 hover:bg-emerald-900 hover:text-white transition font-medium text-center border border-gray-100">
                    + Tambah Siswa
                </a>
                <a href="admin_nilai.php" class="p-3 rounded-xl bg-gray-50 hover:bg-emerald-900 hover:text-white transition font-medium text-center border border-gray-100">
                    + Input Nilai
                </a>
                <a href="admin_absensi.php" class="p-3 rounded-xl bg-gray-50 hover:bg-emerald-900 hover:text-white transition font-medium text-center border border-gray-100">
                    + Catat Absensi
                </a>
                <a href="admin_absensi_arsip.php" class="p-3 rounded-xl bg-gray-50 hover:bg-emerald-900 hover:text-white transition font-medium text-center border border-gray-100">
                    📂 Arsip Bulanan
                </a>
            </div>
        </div>

    </div>
</body>
</html>