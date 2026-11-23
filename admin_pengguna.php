<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_dashboard.php");
    exit;
}

if (isset($_POST['tambah_user'])) {
    $username  = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password  = $_POST['password'];
    $nama_user = mysqli_real_escape_string($koneksi, $_POST['nama_user']);
    $role      = mysqli_real_escape_string($koneksi, $_POST['role']);
    $nis       = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $kelas     = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $jurusan   = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $jk        = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);

    mysqli_query($koneksi, "INSERT INTO user (username, password, nama_user, role, nis, nama, kelas, jurusan, jenis_kelamin) VALUES ('$username', '$password', '$nama_user', '$role', '$nis', '$nama_user', '$kelas', '$jurusan', '$jk')");
    header("Location: admin_pengguna.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    // Keamanan ekstra: Pastikan akun admin utama tidak bisa dihapus via URL parameter
    $cek_admin = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user='$id'");
    $data_cek = mysqli_fetch_assoc($cek_admin);
    if($data_cek && $data_cek['role'] != 'admin') {
        mysqli_query($koneksi, "DELETE FROM user WHERE id_user='$id'");
    }
    header("Location: admin_pengguna.php");
    exit;
}

$data_user = mysqli_query($koneksi, "SELECT * FROM user ORDER BY role ASC, nama_user ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengguna - SIRENIL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #F5F0E6; color: #1E3B2F; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow: hidden; }
    </style>
</head>
<body class="p-3 md:p-5 h-screen flex flex-col justify-between">
    <div class="max-w-6xl mx-auto w-full flex flex-col h-full">
        <?php include 'navbar.php'; ?>

        <!-- Header Judul Estetik -->
        <div class="mb-4 bg-white px-5 py-3.5 rounded-xl shadow-sm border border-emerald-900/10 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-black tracking-wide" style="color: #1E3B2F;">Kelola Pengguna Sistem</h1>
                <p class="text-[11px] text-gray-500">Tambah, pantau, dan atur hak akses akun administrator, guru, dan siswa.</p>
            </div>
            <span class="px-3 py-1 text-xs font-bold rounded-lg border" style="background-color: #1E3B2F; color: #F5F0E6; border-color: #1E3B2F;">
                Panel Admin Utama
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start pb-4">
            <!-- Form Tambah Pengguna dengan Scroll Internal -->
            <div class="bg-white p-5 rounded-2xl shadow-md border-t-4 max-h-[calc(100vh-210px)] overflow-y-auto" style="border-color: #1E3B2F;">
                <h3 class="font-bold text-sm mb-3 flex items-center gap-2" style="color: #1E3B2F;">
                    <svg class="w-4 h-4 text-emerald-800" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Pengguna Baru
                </h3>
                <form method="POST" class="space-y-2.5 text-xs">
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Username</label>
                        <input type="text" name="username" required placeholder="Contoh: budi_guru" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Password</label>
                        <input type="password" name="password" required placeholder="Masukkan password..." class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Nama Lengkap</label>
                        <input type="text" name="nama_user" required placeholder="Nama lengkap & gelar..." class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Role / Peran</label>
                        <select name="role" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50 font-medium">
                            <option value="admin">Admin</option>
                            <option value="guru">Guru</option>
                            <option value="siswa">Siswa</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">NIS (Khusus Siswa)</label>
                        <input type="text" name="nis" placeholder="Nomor Induk Siswa..." class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Kelas (Khusus Siswa)</label>
                        <input type="text" name="kelas" placeholder="Contoh: XI RPL 1" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Jurusan (Khusus Siswa)</label>
                        <input type="text" name="jurusan" placeholder="Contoh: RPL" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider mb-0.5 text-gray-600">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] bg-gray-50/50 font-medium">
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="pt-1">
                        <button type="submit" name="tambah_user" class="w-full text-white py-2.5 rounded-lg font-bold transition shadow-md hover:opacity-95 text-xs tracking-wide" style="background-color: #1E3B2F;">
                            Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Data Pengguna -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100 max-h-[calc(100vh-210px)] flex flex-col">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-xs text-gray-700 uppercase tracking-wider">Daftar Akun Terdaftar</h3>
                    <span class="text-[11px] text-gray-400 font-medium">Aktif</span>
                </div>
                <div class="overflow-y-auto flex-1">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="sticky top-0 text-white uppercase tracking-wider z-10" style="background-color: #1E3B2F;">
                            <tr>
                                <th class="p-3 pl-4">Nama</th>
                                <th class="p-3">Username</th>
                                <th class="p-3">Role</th>
                                <th class="p-3">Kelas/Info</th>
                                <th class="p-3 text-center pr-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php while($u = mysqli_fetch_assoc($data_user)): ?>
                            <tr class="hover:bg-emerald-50/30 transition duration-150">
                                <td class="p-3 pl-4 font-semibold text-gray-800"><?= htmlspecialchars($u['nama_user'] ?? $u['nama'] ?? '') ?></td>
                                <td class="p-3 text-gray-600 font-mono text-[11px]"><?= htmlspecialchars($u['username'] ?? '') ?></td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold tracking-wide shadow-sm 
                                        <?= $u['role']=='admin' ? 'bg-red-100 text-red-700 border border-red-200' : ($u['role']=='guru' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-blue-100 text-blue-700 border border-blue-200') ?>">
                                        <?= strtoupper($u['role']) ?>
                                    </span>
                                </td>
                                <td class="p-3 text-gray-600 font-medium"><?= htmlspecialchars($u['kelas'] ?? '-') ?></td>
                                <td class="p-3 text-center pr-4">
                                    <?php if($u['role'] == 'admin'): ?>
                                        <span class="text-gray-400 italic text-[11px] bg-gray-100 px-2 py-1 rounded">Permanent</span>
                                    <?php else: ?>
                                        <a href="edit_pengguna.php?id=<?= (int)$u['id_user'] ?>" class="inline-block px-2 py-1 mr-1 rounded-md bg-emerald-50 text-emerald-700 hover:bg-emerald-700 hover:text-white transition font-bold shadow-sm">Edit</a>
                                        <a href="?hapus=<?= (int)$u['id_user'] ?>" onclick="return confirm('Hapus pengguna ini?')" class="px-2 py-1 rounded-md bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition font-bold shadow-sm">
                                            Hapus
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>