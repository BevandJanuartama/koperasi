<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Riwayat Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f9fafb; }
        .sticky-month { position: sticky; top: 0; z-index: 10; }
    </style>
</head>
<body class="text-gray-900">

    <div class="p-6">
    <form action="{{ route('siswa.riwayat.seluruhnya') }}" method="GET" class="mb-6 bg-gray-50 p-4 rounded-2xl border border-gray-100">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full mt-1 p-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full mt-1 p-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>
        </div>
        <div class="flex gap-2 mt-4">
            <button type="submit" class="flex-1 bg-emerald-600 text-white py-2 rounded-lg font-bold text-sm hover:bg-emerald-700 transition">Filter</button>
            <a href="{{ route('siswa.riwayat.seluruhnya') }}" class="px-4 bg-gray-200 text-gray-600 py-2 rounded-lg font-bold text-sm hover:bg-gray-300 transition text-center">Reset</a>
        </div>
    </form>

    <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="bg-green-50 p-4 rounded-2xl border border-green-100">
            <p class="text-[10px] text-green-600 font-bold uppercase mb-1 tracking-wider">Total Masuk</p>
            <p class="text-lg font-extrabold text-green-700">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
        </div>
        <div class="bg-red-50 p-4 rounded-2xl border border-red-100">
            <p class="text-[10px] text-red-600 font-bold uppercase mb-1 tracking-wider">Total Keluar</p>
            <p class="text-lg font-extrabold text-red-700">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="flex items-center gap-2 mb-4">
        <h2 class="font-bold text-gray-800">Daftar Transaksi</h2>
        @if($startDate && $endDate)
            <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded font-bold">Filtered</span>
        @endif
    </div>

    @if($allHistory->isEmpty())
        @else
        @php $currentMonth = null; @endphp
        <div class="space-y-1">
            @foreach($allHistory as $item)
                @php
                    $date = \Carbon\Carbon::parse($item->created_at);
                    $monthYear = $date->translatedFormat('F Y');
                    $isIncome = ($item->type === 'topup');
                @endphp

                @if($monthYear !== $currentMonth)
                    @php $currentMonth = $monthYear; @endphp
                    <div class="pt-6 pb-2">
                        <span class="text-xs font-black uppercase tracking-widest text-gray-400">{{ $monthYear }}</span>
                    </div>
                @endif

                <div class="flex justify-between items-center py-4 border-b border-gray-50 last:border-0">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $isIncome ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            @if($isIncome)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 01 1.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-sm">{{ $isIncome ? 'Isi Saldo (Top Up)' : 'Pembayaran Belanja' }}</p>
                            <p class="text-[11px] text-gray-400 font-medium">{{ $date->translatedFormat('d M Y') }} • {{ $date->format('H:i') }} WITA</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold {{ $isIncome ? 'text-green-600' : 'text-red-600' }}">
                            {{ $isIncome ? '+' : '-' }} Rp {{ number_format($item->amount, 0, ',', '.') }}
                        </p>
                        <p class="text-[10px] text-gray-300 italic">Berhasil</p>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-10 mb-6">
            {{ $allHistory->links() }}
        </div>
    @endif
</div>

</body>
</html>