<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'guru')) {
    header("Location: login.php");
    exit;
}

$bulan = isset($_GET['bulan']) ? mysqli_real_escape_string($koneksi, $_GET['bulan']) : date('Y-m');
$is_arsip = isset($_GET['arsip']);

if ($is_arsip) {
    $q_str = "SELECT absensi_arsip.*, user.nama, user.nis, user.kelas FROM absensi_arsip JOIN user ON absensi_arsip.id_user = user.id_user WHERE 1=1";
    if (!empty($bulan)) {
        $q_str .= " AND absensi_arsip.bulan_tahun = '$bulan'";
    }
    $q_str .= " ORDER BY absensi_arsip.tanggal DESC";
} else {
    $q_str = "SELECT absensi.*, user.nama, user.nis, user.kelas FROM absensi JOIN user ON absensi.id_user = user.id_user WHERE DATE_FORMAT(absensi.tanggal, '%Y-%m') = '$bulan' ORDER BY absensi.tanggal DESC";
}

$query = mysqli_query($koneksi, $q_str);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kehadiran Siswa</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        h2, h3 { text-align: center; color: #1E3B2F; margin-bottom: 5px; }
        p { text-align: center; color: #555; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #7F8C6A; }
        th { background-color: #1E3B2F; color: white; padding: 8px; font-size: 12px; }
        td { padding: 8px; font-size: 12px; }
    </style>
</head>
<body onload="window.print()">
    <h2>LAPORAN KEHADIRAN SISWA SIRENIL</h2>
    <h3><?= $is_arsip ? "ARSIP BULAN: " . ($bulan ? $bulan : 'SEMUA') : "BULAN: " . $bulan ?></h3>
    <p>Dicetak pada: <?= date('d-m-Y') ?></p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td style="text-align:center;"><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nis'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['kelas'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['tanggal']) ?></td>
                <td style="text-align:center; font-weight:bold;"><?= htmlspecialchars($row['keterangan']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>