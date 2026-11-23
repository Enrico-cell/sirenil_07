<?php
session_start(); include 'koneksi.php';
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','guru'], true)) { header('Location: login.php'); exit; }
$id=(int)($_GET['id'] ?? $_POST['id'] ?? 0);
if ($_SERVER['REQUEST_METHOD']==='POST') {
 $nama=mysqli_real_escape_string($koneksi,trim($_POST['nama'] ?? '')); $nis=mysqli_real_escape_string($koneksi,trim($_POST['nis'] ?? ''));
 $kelas=mysqli_real_escape_string($koneksi,trim($_POST['kelas'] ?? '')); $jurusan=mysqli_real_escape_string($koneksi,trim($_POST['jurusan'] ?? '')); $jk=mysqli_real_escape_string($koneksi,$_POST['jenis_kelamin'] ?? '');
 $ok=mysqli_query($koneksi,"UPDATE user SET nama='$nama', nama_user='$nama', nis='$nis', kelas='$kelas', jurusan='$jurusan', jenis_kelamin='$jk' WHERE id_user=$id AND role='siswa'");
 if(!$ok) die('Gagal memperbarui siswa: '.mysqli_error($koneksi)); header('Location: admin_siswa.php'); exit;
}
$r=mysqli_query($koneksi,"SELECT * FROM user WHERE id_user=$id AND role='siswa'"); $d=$r?mysqli_fetch_assoc($r):null; if(!$d) die('Data siswa tidak ditemukan.');
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Edit Data - SIRENIL</title><script src="https://cdn.tailwindcss.com"></script></head><body class="min-h-screen p-4 md:p-8" style="background:#F5F0E6;color:#1E3B2F;font-family:Segoe UI,Tahoma,sans-serif"><main class="max-w-2xl mx-auto"><div class="bg-white rounded-2xl shadow p-6"><h1 class="text-xl font-black mb-1">Edit Data</h1><p class="text-sm text-gray-500 mb-5">Ubah informasi lalu tekan Simpan Perubahan.</p><form method="post" class="space-y-4"><input type="hidden" name="id" value="<?= (int)$id ?>">
<div><label class="block text-xs font-bold text-gray-600 mb-1">Nama Lengkap</label><input name="nama" value="<?= htmlspecialchars($d['nama'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-50" required></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">NIS</label><input name="nis" value="<?= htmlspecialchars($d['nis'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-50" required></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">Kelas</label><input name="kelas" value="<?= htmlspecialchars($d['kelas'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-50" required></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">Jurusan</label><input name="jurusan" value="<?= htmlspecialchars($d['jurusan'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-50" required></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">Jenis Kelamin</label><select name="jenis_kelamin" class="w-full px-3 py-2 border rounded-lg bg-gray-50"><option value="Laki-laki" <?= ($d['jenis_kelamin']??'')==='Laki-laki'?'selected':'' ?>>Laki-laki</option><option value="Perempuan" <?= ($d['jenis_kelamin']??'')==='Perempuan'?'selected':'' ?>>Perempuan</option></select></div>
<div class="flex gap-2 pt-2"><button class="flex-1 rounded-lg px-4 py-2.5 text-white font-bold" style="background:#1E3B2F" type="submit">Simpan Perubahan</button><a class="rounded-lg px-4 py-2.5 bg-gray-100 font-bold" href="admin_siswa.php">Batal</a></div></form></div></main></body></html>