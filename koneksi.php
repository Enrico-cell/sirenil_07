<?php
$host = "localhost";
$user = "root";
$pass = ""; // Default Laragon kosong
$db   = "recap_nilai";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>
