<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'guru')) {
    header("Location: login.php");
    exit;
}

// Tambah Nilai Baru
if (isset($_POST['simpan_nilai'])) {
    $id_siswa  = isset($_POST['id_siswa']) ? mysqli_real_escape_string($koneksi, $_POST['id_siswa']) : '';
    $mapel     = isset($_POST['mapel']) ? mysqli_real_escape_string($koneksi, $_POST['mapel']) : '';
    $uh        = isset($_POST['uh']) ? mysqli_real_escape_string($koneksi, $_POST['uh']) : '';
    $uts       = isset($_POST['uts']) ? mysqli_real_escape_string($koneksi, $_POST['uts']) : '';
    $uas       = isset($_POST['uas']) ? mysqli_real_escape_string($koneksi, $_POST['uas']) : '';
    $semester  = isset($_POST['semester']) ? mysqli_real_escape_string($koneksi, $_POST['semester']) : '';

    if (!empty($id_siswa) && !empty($mapel)) {
        // Menyertakan kolom nilai untuk menghindari error default value dari database
        $query = "INSERT INTO nilai
          (id_user, mata_pelajaran, UH, UTS, UAS, semester)
          VALUES
          ('$id_siswa', '$mapel', '$uh', '$uts', '$uas', '$semester')";

mysqli_query($koneksi, $query);
    }
    header("Location: admin_nilai.php");
    exit;
}

// Hapus Nilai
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM nilai WHERE id_nilai='$id'");
    header("Location: admin_nilai.php");
    exit;
}

$data_siswa = mysqli_query($koneksi, "SELECT * FROM user WHERE role='siswa' ORDER BY nama ASC");
$data_nilai = mysqli_query($koneksi, "SELECT nilai.*, user.nama, user.nama_user, user.nis, user.kelas FROM nilai JOIN user ON nilai.id_user = user.id_user ORDER BY user.nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Nilai Siswa - SIRENIL</title>
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
                <h1 class="text-xl font-black tracking-wide" style="color: #1E3B2F;">Kelola Nilai Siswa</h1>
                <p class="text-[11px] text-gray-500">Pusat input dan rekapitulasi nilai UH, UTS, dan UAS siswa.</p>
            </div>
            <a href="admin_dashboard.php" class="px-4 py-1.5 rounded-xl text-xs font-bold transition shadow-sm" style="background-color: #1E3B2F; color: #F5F0E6;">
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start pb-4">
            <!-- Form Input Nilai dengan Scroll Internal -->
            <div class="bg-white p-5 rounded-2xl shadow-md border-t-4 max-h-[calc(100vh-210px)] overflow-y-auto" style="border-color: #1E3B2F;">
                <h3 class="font-bold text-sm mb-3 flex items-center gap-2" style="color: #1E3B2F;">
                    <svg class="w-4 h-4 text-emerald-800" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Input Nilai Baru
                </h3>
                <form method="POST" class="space-y-2.5 text-xs">
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Pilih Siswa</label>
                        <select name="id_siswa" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50 font-medium">
                            <option value="">-- Pilih Siswa --</option>
                            <?php while($s = mysqli_fetch_assoc($data_siswa)): ?>
                                <option value="<?= $s['id_user'] ?>"><?= htmlspecialchars($s['nama'] ?? $s['nama_user']) ?> (<?= htmlspecialchars($s['kelas'] ?? '-') ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Mata Pelajaran</label>
                        <input type="text" name="mapel" required placeholder="Contoh: Matematika" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50">
                    </div>
                    
                    <!-- Input UH, UTS, UAS -->
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">UH</label>
                            <input type="text" name="uh" placeholder="Nilai..." class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50 text-center">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">UTS</label>
                            <input type="text" name="uts" placeholder="Nilai..." class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50 text-center">
                        </div>
                        <div>
                            <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">UAS</label>
                            <input type="text" name="uas" placeholder="Nilai..." class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50 text-center">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Semester</label>
                        <select name="semester" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50 font-medium">
                            <option value="Ganjil">Semester Ganjil</option>
                            <option value="Genap">Semester Genap</option>
                        </select>
                    </div>
                    <div class="pt-1">
                        <button type="submit" name="simpan_nilai" class="w-full text-white py-2.5 rounded-lg font-bold transition shadow-md hover:opacity-95 text-xs tracking-wide" style="background-color: #1E3B2F;">
                            Simpan Nilai
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Data Rekapitulasi Nilai -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100 max-h-[calc(100vh-210px)] flex flex-col">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-xs text-gray-700 uppercase tracking-wider">Rekapitulasi Nilai Siswa</h3>
                    <span class="text-[11px] text-gray-400 font-medium">Data Akademik</span>
                </div>
                <div class="overflow-y-auto flex-1">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="sticky top-0 text-white uppercase tracking-wider z-10" style="background-color: #1E3B2F;">
                            <tr>
                                <th class="p-3 pl-4">Siswa</th>
                                <th class="p-3">Mapel</th>
                                <th class="p-3 text-center">UH</th>
                                <th class="p-3 text-center">UTS</th>
                                <th class="p-3 text-center">UAS</th>
                                <th class="p-3 text-center">Semester</th>
                                <th class="p-3 text-center pr-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if($data_nilai && mysqli_num_rows($data_nilai) > 0): ?>
                                <?php while($n = mysqli_fetch_assoc($data_nilai)): ?>
                                <tr class="hover:bg-emerald-50/30 transition duration-150">
                                    <td class="p-3 pl-4 font-semibold text-gray-800">
                                        <?= htmlspecialchars($n['nama'] ?? $n['nama_user'] ?? '') ?>
                                        <div class="text-[10px] text-gray-400 font-normal"><?= htmlspecialchars($n['kelas'] ?? '-') ?></div>
                                    </td>
                                    <td class="p-3 text-gray-700 font-medium"><?= htmlspecialchars($n['mata_pelajaran'] ?? '-') ?></td>
                                    <td class="p-3 text-center text-gray-700"><?= htmlspecialchars($n['UH'] ?? '-') ?></td>
                                    <td class="p-3 text-center text-gray-700"><?= htmlspecialchars($n['UTS'] ?? '-') ?></td>
                                    <td class="p-3 text-center text-gray-700"><?= htmlspecialchars($n['UAS'] ?? '-') ?></td>
                                    <td class="p-3 text-center">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            <?= htmlspecialchars($n['semester'] ?? '-') ?>
                                        </span>
                                    </td>
                                    <td class="p-3 text-center pr-4">
                                        <a href="edit_nilai.php?id=<?= (int)$n['id_nilai'] ?>" class="inline-block px-2 py-1 mr-1 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-700 hover:text-white transition font-bold shadow-sm">Edit</a>
                                        <a href="?hapus=<?= $n['id_nilai'] ?>" onclick="return confirm('Hapus data nilai ini?')" class="px-2 py-1 rounded-md bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition font-bold shadow-sm">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="p-6 text-center text-gray-400">Belum ada data nilai tersimpan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>