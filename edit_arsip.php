<?php
session_start(); include 'koneksi.php';
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','guru'], true)) { header('Location: login.php'); exit; }
$id=(int)($_GET['id'] ?? $_POST['id'] ?? 0);
if ($_SERVER['REQUEST_METHOD']==='POST') {
 $bulan=mysqli_real_escape_string($koneksi,trim($_POST['bulan'] ?? '')); $ket=mysqli_real_escape_string($koneksi,trim($_POST['keterangan'] ?? '')); $tgl=mysqli_real_escape_string($koneksi,$_POST['tanggal_arsip'] ?? '');
 $ok=mysqli_query($koneksi,"UPDATE absensi_arsip SET bulan='$bulan', keterangan='$ket', tanggal_arsip='$tgl' WHERE id_arsip=$id");
 if(!$ok) die('Gagal memperbarui arsip: '.mysqli_error($koneksi)); header('Location: admin_absensi_arsip.php'); exit;
}
$r=mysqli_query($koneksi,"SELECT * FROM absensi_arsip WHERE id_arsip=$id"); $d=$r?mysqli_fetch_assoc($r):null; if(!$d) die('Data arsip tidak ditemukan.');
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Edit Data - SIRENIL</title><script src="https://cdn.tailwindcss.com"></script></head><body class="min-h-screen p-4 md:p-8" style="background:#F5F0E6;color:#1E3B2F;font-family:Segoe UI,Tahoma,sans-serif"><main class="max-w-2xl mx-auto"><div class="bg-white rounded-2xl shadow p-6"><h1 class="text-xl font-black mb-1">Edit Data</h1><p class="text-sm text-gray-500 mb-5">Ubah informasi lalu tekan Simpan Perubahan.</p><form method="post" class="space-y-4"><input type="hidden" name="id" value="<?= (int)$id ?>">
<div><label class="block text-xs font-bold text-gray-600 mb-1">Periode Bulan</label><input name="bulan" value="<?= htmlspecialchars($d['bulan'] ?? '') ?>" required class="w-full px-3 py-2 border rounded-lg bg-gray-50"></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">Keterangan</label><input name="keterangan" value="<?= htmlspecialchars($d['keterangan'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-50"></div>
<div><label class="block text-xs font-bold text-gray-600 mb-1">Tanggal Arsip</label><input type="date" name="tanggal_arsip" value="<?= htmlspecialchars($d['tanggal_arsip'] ?? '') ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-50"></div>
<div class="flex gap-2 pt-2"><button class="flex-1 rounded-lg px-4 py-2.5 text-white font-bold" style="background:#1E3B2F" type="submit">Simpan Perubahan</button><a class="rounded-lg px-4 py-2.5 bg-gray-100 font-bold" href="admin_absensi_arsip.php">Batal</a></div></form></div></main></body></html>