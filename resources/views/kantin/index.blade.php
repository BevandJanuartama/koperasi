<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Kantin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center font-sans">

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

    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-emerald-700">Selamat Datang di Kantin Sekolah</h1>
        <p class="text-gray-600 mt-2">Silakan pilih menu untuk melanjutkan</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 w-11/12 max-w-4xl">
        <!-- CARD TRANSAKSI BARU -->
        <a href="{{ route('kantin.kasir') }}" class="block bg-white rounded-2xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
            <div class="p-6 text-center">
                <div class="flex justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.4 7H20m-6-7v7m-4-7v7" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Transaksi Baru</h2>
                <p class="text-gray-500 mt-2">Mulai pemindaian barang dan proses pembayaran.</p>
            </div>
        </a>

        <!-- CARD RIWAYAT TRANSAKSI -->
        <a href="{{ route('kantin.transaksi') }}" class="block bg-white rounded-2xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
            <div class="p-6 text-center">
                <div class="flex justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Riwayat Transaksi</h2>
                <p class="text-gray-500 mt-2">Lihat catatan transaksi terakhir di kantin.</p>
            </div>
        </a>
    </div>

</body>
</html>
