<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    </style>
</head>
<body class="p-4">
    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
            
            <div class="bg-indigo-600 text-white p-6">
                <h1 class="text-2xl font-bold mb-1">Nota Transaksi #{{ $transaksi->id }}</h1>
                <p class="text-sm opacity-90">Tanggal: {{ \Carbon\Carbon::parse($transaksi->created_at)->format('d F Y, H:i:s') }} WITA</p>
            </div>

            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Rincian Pembelian</h2>
                
                <ul class="divide-y divide-gray-200 mb-6">
                    @foreach ($transaksi->details as $detail)
                        <li class="py-3 flex justify-between items-center text-gray-700">
                            <div class="flex-1 pr-4">
                                <div class="font-medium">{{ $detail->barang->nama_barang ?? 'Barang Dihapus' }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $detail->qty }} x Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="font-bold text-right text-base">
                                Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-6 pt-4 border-t border-dashed border-gray-400 flex justify-between items-center">
                    <span class="text-2xl font-extrabold text-gray-800">TOTAL HARGA:</span>
                    <span class="text-3xl font-extrabold text-green-600">
                        Rp{{ number_format($transaksi->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t text-center">
                <a href="{{ route('siswa.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali ke Riwayat
                </a>
            </div>

        </div>
    </div>
</body>
</html>
