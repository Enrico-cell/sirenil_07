<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'guru')) {
    header("Location: login.php");
    exit;
}

$kelas = isset($_GET['kelas']) ? mysqli_real_escape_string($koneksi, $_GET['kelas']) : '';
$query_str = "SELECT * FROM user WHERE role='siswa'";
if (!empty($kelas)) {
    $query_str .= " AND kelas = '$kelas'";
}
$query_str .= " ORDER BY nama ASC";
$query = mysqli_query($koneksi, $query_str);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Siswa</title>
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
    <h2>LAPORAN DATA SISWA SIRENIL</h2>
    <h3><?= !empty($kelas) ? "KELAS: " . htmlspecialchars($kelas) : "SEMUA KELAS" ?></h3>
    <p>Dicetak pada: <?= date('d-m-Y') ?></p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Jenis Kelamin</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td style="text-align:center;"><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nis'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['nama'] ?? $row['nama_user']) ?></td>
                <td><?= htmlspecialchars($row['kelas'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['jurusan'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['jenis_kelamin'] ?? '-') ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>