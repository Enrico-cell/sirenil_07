<?php
session_start(); include 'koneksi.php';
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','guru'], true)) { header('Location: login.php'); exit; }
$id=(int)($_GET['id'] ?? $_POST['id'] ?? 0);
if ($_SERVER['REQUEST_METHOD']==='POST') {
 $uid=(int)($_POST['id_siswa'] ?? 0); $mapel=mysqli_real_escape_string($koneksi,$_POST['mapel'] ?? '');
 $uh=mysqli_real_escape_string($koneksi,$_POST['uh'] ?? '0'); $uts=mysqli_real_escape_string($koneksi,$_POST['uts'] ?? '0'); $uas=mysqli_real_escape_string($koneksi,$_POST['uas'] ?? '0'); $semester=mysqli_real_escape_string($koneksi,$_POST['semester'] ?? 'Ganjil');
 $ok=mysqli_query($koneksi,"UPDATE nilai SET id_user=$uid, mata_pelajaran='$mapel', UH='$uh', UTS='$uts', UAS='$uas', semester='$semester' WHERE id_nilai=$id");
 if(!$ok) die('Gagal memperbarui nilai: '.mysqli_error($koneksi)); header('Location: admin_nilai.php'); exit;
}
$r=mysqli_query($koneksi,"SELECT * FROM nilai WHERE id_nilai=$id"); $d=$r?mysqli_fetch_assoc($r):null; if(!$d) die('Data nilai tidak ditemukan.');
$siswa=mysqli_query($koneksi,"SELECT id_user,nama,nama_user,kelas FROM user WHERE role='siswa' ORDER BY nama ASC");
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Edit Data - SIRENIL</title><script src="https://cdn.tailwindcss.com"></script></head><body class="min-h-screen p-4 md:p-8" style="background:#F5F0E6;color:#1E3B2F;font-family:Segoe UI,Tahoma,sans-serif"><main class="max-w-2xl mx-auto"><div class="bg-white rounded-2xl shadow p-6"><h1 class="text-xl font-black mb-1">Edit Data</h1><p class="text-sm text-gray-500 mb-5">Ubah informasi lalu tekan Simpan Perubahan.</p><form method="post" class="space-y-4"><input type="hidden" name="id" value="<?= (int)$id ?>">
<div><label class="block text-xs font-bold text-gray-600 mb-1">Pilih Siswa</label><select name="id_siswa" required class="w-full px-3 py-2 border rounded-lg bg-gray-50"><?php while($s=mysqli_fetch_assoc($siswa)): ?><option value="<?= (int)$s['id_user'] ?>" <?= (int)$d['id_user']===(int)$s['id_user']?'selected':'' ?>><?= htmlspecialchars($s['nama'] ?: $s['nama_user']) ?> (<?= htmlspecialchars($s['kelas'] ?? '-') ?>)</option><?php endwhile; ?></select></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">Mata Pelajaran</label><input name="mapel" value="<?= htmlspecialchars($d['mata_pelajaran'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-50" required></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">Nilai UH</label><input name="uh" value="<?= htmlspecialchars($d['UH'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-50" ></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">Nilai UTS</label><input name="uts" value="<?= htmlspecialchars($d['UTS'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-50" ></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">Nilai UAS</label><input name="uas" value="<?= htmlspecialchars($d['UAS'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-50" ></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">Semester</label><select name="semester" class="w-full px-3 py-2 border rounded-lg bg-gray-50"><option value="Ganjil" <?= ($d['semester']??'')==='Ganjil'?'selected':'' ?>>Ganjil</option><option value="Genap" <?= ($d['semester']??'')==='Genap'?'selected':'' ?>>Genap</option></select></div>
<div class="flex gap-2 pt-2"><button class="flex-1 rounded-lg px-4 py-2.5 text-white font-bold" style="background:#1E3B2F" type="submit">Simpan Perubahan</button><a class="rounded-lg px-4 py-2.5 bg-gray-100 font-bold" href="admin_nilai.php">Batal</a></div></form></div></main></body></html>