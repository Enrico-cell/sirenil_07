<?php
session_start(); include 'koneksi.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header('Location: admin_dashboard.php'); exit; }
$id=(int)($_GET['id'] ?? $_POST['id'] ?? 0);
// Akun administrator bersifat permanen dan tidak boleh diedit, termasuk melalui URL langsung.
$cek_target = mysqli_query($koneksi, "SELECT role FROM user WHERE id_user=$id LIMIT 1");
$target_user = $cek_target ? mysqli_fetch_assoc($cek_target) : null;
if (!$target_user) { die('Data pengguna tidak ditemukan.'); }
if ($target_user['role'] === 'admin') {
    header('Location: admin_pengguna.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD']==='POST') {
 $username=mysqli_real_escape_string($koneksi,trim($_POST['username'] ?? '')); $nama=mysqli_real_escape_string($koneksi,trim($_POST['nama_user'] ?? ''));
 $role=mysqli_real_escape_string($koneksi,$_POST['role'] ?? 'siswa'); $nis=mysqli_real_escape_string($koneksi,trim($_POST['nis'] ?? ''));
 $kelas=mysqli_real_escape_string($koneksi,trim($_POST['kelas'] ?? '')); $jurusan=mysqli_real_escape_string($koneksi,trim($_POST['jurusan'] ?? '')); $jk=mysqli_real_escape_string($koneksi,$_POST['jenis_kelamin'] ?? '');
 $password=trim($_POST['password'] ?? '');
 $sql="UPDATE user SET username='$username', nama_user='$nama', nama='$nama', role='$role', nis='$nis', kelas='$kelas', jurusan='$jurusan', jenis_kelamin='$jk'";
 if($password!==''){ $pw=mysqli_real_escape_string($koneksi,$password); $sql.=" , password='$pw'"; }
 $sql.=" WHERE id_user=$id";
 $ok=mysqli_query($koneksi,$sql); if(!$ok) die('Gagal memperbarui pengguna: '.mysqli_error($koneksi)); header('Location: admin_pengguna.php'); exit;
}
$r=mysqli_query($koneksi,"SELECT * FROM user WHERE id_user=$id"); $d=$r?mysqli_fetch_assoc($r):null; if(!$d) die('Data pengguna tidak ditemukan.');
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Edit Data - SIRENIL</title><script src="https://cdn.tailwindcss.com"></script></head><body class="min-h-screen p-4 md:p-8" style="background:#F5F0E6;color:#1E3B2F;font-family:Segoe UI,Tahoma,sans-serif"><main class="max-w-2xl mx-auto"><div class="bg-white rounded-2xl shadow p-6"><h1 class="text-xl font-black mb-1">Edit Data</h1><p class="text-sm text-gray-500 mb-5">Ubah informasi lalu tekan Simpan Perubahan.</p><form method="post" class="space-y-4"><input type="hidden" name="id" value="<?= (int)$id ?>">
<div><label class="block text-xs font-bold text-gray-600 mb-1">Username</label><input name="username" value="<?= htmlspecialchars($d['username'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-50" required></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">Nama Lengkap</label><input name="nama_user" value="<?= htmlspecialchars($d['nama_user'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-50" required></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">NIS</label><input name="nis" value="<?= htmlspecialchars($d['nis'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-50" ></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">Kelas</label><input name="kelas" value="<?= htmlspecialchars($d['kelas'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-50" ></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">Jurusan</label><input name="jurusan" value="<?= htmlspecialchars($d['jurusan'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-50" ></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">Password Baru (kosongkan jika tidak diubah)</label><input type="password" name="password" class="w-full px-3 py-2 border rounded-lg bg-gray-50"></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">Role</label><select name="role" class="w-full px-3 py-2 border rounded-lg bg-gray-50"><?php foreach(['admin','guru','siswa'] as $role): ?><option value="<?= $role ?>" <?= $d['role']===$role?'selected':'' ?>><?= ucfirst($role) ?></option><?php endforeach; ?></select></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">Jenis Kelamin</label><select name="jenis_kelamin" class="w-full px-3 py-2 border rounded-lg bg-gray-50"><option value="">-- Pilih --</option><option value="Laki-laki" <?= ($d['jenis_kelamin']??'')==='Laki-laki'?'selected':'' ?>>Laki-laki</option><option value="Perempuan" <?= ($d['jenis_kelamin']??'')==='Perempuan'?'selected':'' ?>>Perempuan</option></select></div>
<div class="flex gap-2 pt-2"><button class="flex-1 rounded-lg px-4 py-2.5 text-white font-bold" style="background:#1E3B2F" type="submit">Simpan Perubahan</button><a class="rounded-lg px-4 py-2.5 bg-gray-100 font-bold" href="admin_pengguna.php">Batal</a></div></form></div></main></body></html>