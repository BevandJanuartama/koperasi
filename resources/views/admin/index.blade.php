<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Keuangan Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Styling untuk efek kartu yang lebih baik */
        .dashboard-card {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
        }
        .dashboard-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            transform: translateY(-4px);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    
    
    <!-- Navbar (Header) dengan Logout Button -->
    <header class="bg-white shadow-md sticky top-0 z-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <div class="flex items-center space-x-3">
                <!-- Logo/Nama Aplikasi -->
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zM9 10a1 1 0 011-1h.01a1 1 0 011 1v1a1 1 0 01-1 1H10a1 1 0 01-1-1v-1z"></path></svg>
                <span class="text-xl font-extrabold text-gray-800 hidden sm:block">Admin Panel</span>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition duration-150 focus:outline-none focus:ring-2 focus:ring-red-500">
                    <!-- Ikon Logout -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="w-4 h-4 mr-2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" x2="9" y1="12" y2="12" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8">

        <!-- Welcome Section -->
        <div class="bg-indigo-600 p-8 rounded-xl shadow-lg mb-8">
            <h1 class="text-3xl font-extrabold text-white mb-2">Selamat Datang, Admin Utama!</h1>
            <p class="text-indigo-200">Akses cepat ke modul utama sistem keuangan sekolah.</p>
        </div>
        
        <!-- Status/Message Box (Untuk simulasi Logout) -->
        <div id="status-message" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-6" role="alert">
            <span class="block sm:inline"></span>
        </div>

        <!-- Cards (Menu Utama) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
            
            <!-- Modul 1: Topup Saldo Siswa -->
            <!-- DIUBAH: p-6 diubah menjadi px-6 py-10 -->
            <a href="/admin/topup" class="dashboard-card block px-6 py-10 rounded-2xl bg-white text-center border-t-4 border-emerald-500">
                <!-- Ikon (Lucide: Wallet) -->
                <div class="flex justify-center mb-3">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Topup Saldo</h3>
                <p class="text-gray-500 text-sm">Kelola penambahan saldo tunai.</p>
            </a>

            <!-- Modul 2: Daftar Barang & Stok -->
            <!-- DIUBAH: p-6 diubah menjadi px-6 py-10 -->
            <a href="/admin/barang" class="dashboard-card block px-6 py-10 rounded-2xl bg-white text-center border-t-4 border-sky-500">
                <!-- Ikon (Lucide: Box) -->
                <div class="flex justify-center mb-3">
                    <svg class="w-8 h-8 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7L4 7m16 0l-4-4m4 4l-4 4m-4-4V7m0 0H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2h-8z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Inventaris Barang</h3>
                <p class="text-gray-500 text-sm">Lihat, tambah, dan edit stok barang.</p>
            </a>

            <!-- Modul 3: Riwayat Transaksi Topup -->
            <!-- DIUBAH: p-6 diubah menjadi px-6 py-10 -->
            <a href="/admin/data-topup" class="dashboard-card block px-6 py-10 rounded-2xl bg-white text-center border-t-4 border-yellow-500">
                <!-- Ikon (Lucide: Receipt) -->
                <div class="flex justify-center mb-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2-8H7m14 0l-2 2-2-2M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Riwayat Topup</h3>
                <p class="text-gray-500 text-sm">Data transaksi topup dan laporan keuangan.</p>
            </a>

            <!-- Modul 4: Registrasi Kartu -->
                <a href="/admin/registrasi"
                class="dashboard-card block px-6 py-10 rounded-2xl bg-white text-center border-t-4 border-emerald-500 hover:shadow-lg transition">
                    <!-- Ikon (Lucide: Credit Card) -->
                    <div class="flex justify-center mb-3">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 mb-1">Registrasi Kartu</h3>
                    <p class="text-gray-500 text-sm">Pendaftaran pengguna baru & penerbitan kartu RFID.</p>
                </a>
        </div>

        <div class="mt-10 text-center text-gray-400 text-sm">
            &copy; 2025 Sistem Keuangan Sekolah. All rights reserved.
        </div>

    </main>
    
    <script>
        function simulateLogout() {
            // Dalam aplikasi nyata (dengan backend), di sini akan ada:
            // 1. Panggilan API ke endpoint logout server.
            // 2. Penghapusan token sesi/cookie di browser.
            // 3. Pengalihan pengguna ke halaman login.
            
            const messageBox = document.getElementById('status-message');
            
            // Tampilkan pesan simulasi logout
            messageBox.querySelector('span').textContent = 'Anda berhasil logout. (Simulasi) Mengarahkan ke halaman login...';
            messageBox.classList.remove('hidden', 'bg-green-100', 'border-green-400', 'text-green-700');
            messageBox.classList.add('bg-indigo-100', 'border-indigo-400', 'text-indigo-700');
            
            // Dalam aplikasi nyata, Anda akan menggunakan: window.location.href = '/login';
            
            // Sembunyikan pesan setelah 3 detik
            setTimeout(() => {
                messageBox.classList.add('hidden');
                // Di sini Anda dapat menambahkan pengalihan halaman jika ini adalah sistem nyata.
            }, 3000); 
        }
    </script>
</body>
</html>
