<?php
session_start();
include 'koneksi.php';

$error = '';
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username'");
    if (mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);
        if ($password === $row['password']) {
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['role']    = $row['role'];
            $_SESSION['nama']    = $row['nama_user'] ?? $row['nama'];

            if ($row['role'] == 'admin' || $row['role'] == 'guru') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: siswa_dashboard.php");
            }
            exit;
        }
    }
    $error = "Username atau password salah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIRENIL (Sistem Rekapitulasi Nilai & Absensi)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { 
            background-color: #F5F0E6; 
            color: #1E3B2F; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        @keyframes fadeInScale {
            0% { opacity: 0; transform: scale(0.92) translateY(20px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* Animasi Kilat Petir Hijau */
        @keyframes lightningFlash {
            0%, 100% { opacity: 0.2; filter: drop-shadow(0 0 2px #4ade80); }
            50% { opacity: 0.9; filter: drop-shadow(0 0 12px #22c55e) drop-shadow(0 0 20px #10b981); }
            52% { opacity: 0.3; }
            54% { opacity: 0.8; }
        }

        @keyframes shine {
            0% { left: -100%; }
            100% { left: 200%; }
        }

        .animate-card {
            animation: fadeInScale 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .lightning-effect {
            animation: lightningFlash 3.5s infinite ease-in-out;
        }

        .btn-shine {
            position: relative;
            overflow: hidden;
        }
        .btn-shine::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
            transform: skewX(-20deg);
        }
        .btn-shine:hover::after {
            animation: shine 1.2s ease-in-out infinite;
        }

        .custom-input {
            transition: all 0.3s ease;
        }
        .custom-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 59, 47, 0.15);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 md:p-6">
    <div class="animate-card bg-white rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden grid grid-cols-1 md:grid-cols-2 border border-emerald-900/10">
        
        <!-- Sisi Kiri: Banner dengan Animasi Petir & Tanggal -->
        <div class="p-8 md:p-12 flex flex-col justify-between text-white relative overflow-hidden" style="background-color: #1E3B2F;">
            
            <!-- Ikon Petir Hijau Menyala di Background Pinggir -->
            <div class="absolute -right-6 top-10 pointer-events-none lightning-effect opacity-30">
                <svg width="180" height="180" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
            </div>
            <div class="absolute right-20 bottom-10 pointer-events-none lightning-effect opacity-20" style="animation-delay: 1.2s;">
                <svg width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
            </div>

            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <span class="inline-block px-3.5 py-1 rounded-full text-xs font-semibold tracking-wider uppercase shadow-sm" style="background-color: #7F8C6A; color: #F5F0E6;">
                        Portal Akademik
                    </span>
                    <div class="flex items-center gap-1.5 text-xs text-emerald-400 font-bold bg-emerald-950/60 px-3 py-1 rounded-full border border-emerald-500/30">
                        <svg class="w-3.5 h-3.5 text-emerald-400 animate-pulse" fill="currentColor" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                        <span>Live System</span>
                    </div>
                </div>

                <h1 class="text-3xl md:text-4xl font-extrabold tracking-wide mb-3 leading-tight">SIRENIL</h1>
                <p class="text-sm opacity-90 leading-relaxed font-light">
                    Sistem Informasi Terpadu untuk Pengelolaan Rekapitulasi Nilai Akademik dan Pemantauan Kehadiran Absensi Siswa secara Efisien.
                </p>
            </div>

            <!-- Tampilan Tanggal, Bulan, dan Tahun -->
            <div class="my-6 relative z-10 bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/10 flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-emerald-800/60 text-emerald-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-wider opacity-75 font-semibold">Tanggal Hari Ini</p>
                    <p class="text-base font-bold text-emerald-200"><?= date('d F Y') ?></p>
                </div>
            </div>

            <div class="space-y-2.5 relative z-10 text-xs opacity-90">
                <div class="flex items-center space-x-2.5">
                    <div class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_#4ade80]"></div>
                    <span>Manajemen Data Nilai & Semester</span>
                </div>
                <div class="flex items-center space-x-2.5">
                    <div class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_#4ade80]"></div>
                    <span>Pencatatan & Arsip Absensi Bulanan</span>
                </div>
            </div>

            <div class="pt-6 border-t border-white/10 mt-4 relative z-10">
                <p class="text-xs opacity-70">© 2026 SIRENIL. All rights reserved.</p>
            </div>
        </div>

        <!-- Sisi Kanan: Form Login -->
        <div class="p-8 md:p-12 flex flex-col justify-center bg-white relative z-10">
            <div class="mb-6">
                <h2 class="text-2xl font-bold mb-1" style="color: #1E3B2F;">Selamat Datang</h2>
                <p class="text-xs text-gray-500">Silakan masukkan kredensial akun Anda untuk melanjutkan.</p>
            </div>

            <?php if($error): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4 text-xs shadow-sm">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" id="loginForm" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-1 text-gray-600">Username</label>
                    <input type="text" name="username" required placeholder="Masukkan username..." class="custom-input w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] text-sm bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-1 text-gray-600">Password</label>
                    <input type="password" name="password" required placeholder="Masukkan password..." class="custom-input w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#7F8C6A] text-sm bg-gray-50/50">
                </div>
                <div class="pt-2">
                    <button type="submit" name="login" id="btnSubmit" class="btn-shine w-full text-white font-bold py-3.5 rounded-xl transition-all duration-300 shadow-lg hover:shadow-emerald-900/20 active:scale-95 text-sm tracking-wide flex justify-center items-center gap-2" style="background-color: #1E3B2F;">
                        <span id="btnText">Masuk ke Aplikasi</span>
                        <svg id="btnSpinner" class="hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Script loading tombol login -->
    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            document.getElementById('btnText').innerText = 'Memproses...';
            document.getElementById('btnSpinner').classList.remove('hidden');
        });
    </script>
</body>
</html>