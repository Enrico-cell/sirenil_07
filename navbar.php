<nav class="bg-white shadow mb-6 rounded-lg p-4 flex flex-wrap justify-between items-center" style="border-bottom: 3px solid #1E3B2F;">
    <div class="flex items-center space-x-6">
        <span class="font-bold text-lg" style="color: #1E3B2F;">SIRENIL</span>
        <div class="space-x-2 text-sm font-medium">
            <a href="admin_dashboard.php" class="px-3 py-2 rounded hover:bg-gray-100">Dashboard</a>
            <a href="admin_pengguna.php" class="px-3 py-2 rounded hover:bg-gray-100">Kelola Pengguna</a>
            <a href="admin_siswa.php" class="px-3 py-2 rounded hover:bg-gray-100">Data Siswa</a>
            <a href="admin_nilai.php" class="px-3 py-2 rounded hover:bg-gray-100">Kelola Nilai</a>
            <a href="admin_absensi.php" class="px-3 py-2 rounded hover:bg-gray-100">Absensi</a>
            <a href="admin_absensi_arsip.php" class="px-3 py-2 rounded hover:bg-gray-100">Arsip Absensi</a>
        </div>
    </div>
    <div class="flex items-center space-x-4 mt-2 md:mt-0">
        <span class="text-sm text-gray-700">Halo, <b><?= htmlspecialchars($_SESSION['nama'] ?? 'User') ?></b></span>
        <a href="logout.php" class="px-3 py-1.5 rounded text-white text-xs font-semibold" style="background-color: #7F8C6A;">Logout</a>
    </div>
</nav>