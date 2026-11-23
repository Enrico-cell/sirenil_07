<?php
session_start();
include 'koneksi.php';

$query = mysqli_query($koneksi, "SELECT nilai.*, user.nama, user.nis, user.kelas, user.jurusan FROM nilai JOIN user ON nilai.id_user = user.id_user ORDER BY user.nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Nilai</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        h2 { text-align: center; color: #1E3B2F; margin-bottom: 0; }
        p { text-align: center; color: #555; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #7F8C6A; }
        th { background-color: #1E3B2F; color: white; padding: 8px; font-size: 12px; }
        td { padding: 8px; font-size: 12px; }
    </style>
</head>
<body onload="window.print()">
    <h2>REKAPITULASI NILAI SISWA (SIRENIL)</h2>
    <p>Laporan Resmi Hasil Belajar</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas / Jurusan</th>
                <th>Mata Pelajaran</th>
                <th>Semester</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td style="text-align:center;"><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nis'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars(($row['kelas'] ?? '').' '.($row['jurusan'] ?? '')) ?></td>
                <td><?= htmlspecialchars($row['mata_pelajaran']) ?></td>
                <td><?= htmlspecialchars($row['semester']) ?></td>
                <td style="text-align:center; font-weight:bold;"><?= htmlspecialchars($row['nilai']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>