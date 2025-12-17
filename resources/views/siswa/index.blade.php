<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .bg-custom-red { background-color: #E53E3A; }
        .bg-custom-green { background-color: #48BB78; }
        .text-custom-red { color: #E53E3A; }
        .text-custom-green { color: #48BB78; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="max-w-xl mx-auto pb-10">
        <div class="bg-gradient-to-br from-emerald-600 to-teal-500 p-8 rounded-b-[2.5rem] shadow-lg text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-emerald-100 text-sm font-medium mb-1">Selamat Datang,</p>
                    <h1 class="text-2xl font-bold">{{ $user->nama ?? $user->username }}</h1>
                </div>
                <a href="{{ route('siswa.profile') }}" class="bg-white/20 p-2 rounded-full border border-white/30 hover:bg-white/30 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </a>
            </div>

            <div class="mt-8">
                <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider">Saldo Tersedia</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-xl font-semibold text-emerald-100">Rp</span>
                    <span class="text-5xl font-extrabold">{{ number_format($currentBalance, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="px-6 -mt-6 grid grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-1">
                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .75.75v10.638l3.96-4.158a.75.75 0 1 1 1.08 1.04l-5.25 5.5a.75.75 0 0 1-1.08 0l-5.25-5.5a.75.75 0 1 1 1.08-1.04l3.96 4.158V3.75A.75.75 0 0 1 10 3Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-gray-500 uppercase">Pemasukan</span>
                </div>
                <p class="text-lg font-bold text-custom-green">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
            </div>

            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-1">
                    <div class="p-2 bg-red-50 rounded-lg text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 17a.75.75 0 0 1-.75-.75V5.612L5.29 9.77a.75.75 0 0 1-1.08-1.04l5.25-5.5a.75.75 0 0 1 1.08 0l5.25 5.5a.75.75 0 1 1-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0 1 10 17Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-gray-500 uppercase">Pengeluaran</span>
                </div>
                <p class="text-lg font-bold text-custom-red">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="px-6 mt-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Transaksi Hari Ini</h2>
                <span class="text-xs bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full font-bold">Terbaru</span>
            </div>

            @if ($transaksis->isEmpty())
                <div class="bg-gray-100/50 border-2 border-dashed border-gray-200 rounded-3xl p-10 text-center">
                    <p class="text-gray-400 font-medium">Belum ada transaksi untuk hari ini.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($transaksis as $transaksi)
                        @php
                            $isIncome = $transaksi->type === 'topup';
                        @endphp
                        
                        <a @if(!$isIncome) href="{{ route('siswa.transaksi.show', $transaksi->id) }}" @endif 
                           class="flex justify-between items-center p-4 bg-white rounded-2xl shadow-sm border border-gray-50 hover:scale-[1.02] transition-transform cursor-pointer">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ $isIncome ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    @if($isIncome)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $isIncome ? 'Top Up Saldo' : 'Pembayaran' }}</p>
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($transaksi->created_at)->format('H:i') }} WITA</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-extrabold text-lg {{ $isIncome ? 'text-custom-green' : 'text-custom-red' }}">
                                    {{ $isIncome ? '+Rp.' : '-Rp.' }}{{ number_format($transaksi->amount,0, ',', '.') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="mt-8">
                <a href="{{ route('siswa.riwayat.seluruhnya') }}" class="flex items-center justify-center gap-2 w-full py-4 bg-gray-800 text-white rounded-2xl font-bold shadow-lg shadow-gray-200 hover:bg-gray-700 active:scale-95 transition-all">
                    <span>Lihat Riwayat Transaksi</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</body>
</html>