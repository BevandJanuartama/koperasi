<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi #{{ $transaksi->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-3xl font-extrabold mb-4 text-center text-emerald-700">Detail Transaksi </h1>
        <p class="text-center text-gray-500 mb-8">Tanggal: {{ \Carbon\Carbon::parse($transaksi->created_at)->locale('id')->isoFormat('dddd, D MMMM YYYY [pukul] HH:mm') }}</p>

        <div class="bg-white rounded-lg shadow-xl p-6 mb-6 border-t-4 border-blue-500">
            <h2 class="text-xl font-bold mb-4 text-blue-700">Informasi Pelanggan & Transaksi</h2>
            <div class="grid grid-cols-2 gap-4 text-lg">
                <div class="font-medium">Pelanggan:</div>
                <div class="text-right">{{ $transaksi->user->nama ?? 'N/A' }}</div>
                
                <div class="font-medium">Total Pembayaran:</div>
                <div class="text-right font-extrabold text-red-600">Rp{{ number_format($transaksi->total, 0, ',', '.') }}</div>
                
                <div class="font-medium">Metode Pembayaran:</div>
                <div class="text-right">Saldo Kantin/Kartu</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-xl p-6 mb-6 border-t-4 border-emerald-500">
            <h2 class="text-xl font-bold mb-4 text-emerald-700">Item Dibeli</h2>
            
            <table class="w-full text-left rounded-lg overflow-hidden border">
                <thead class="bg-gray-200 uppercase text-sm">
                    <tr>
                        <th class="p-3">Barang</th>
                        <th class="p-3 text-right">Harga Satuan</th>
                        <th class="p-3 text-center">Qty</th>
                        <th class="p-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi->details as $detail)
                        <tr class="border-b hover:bg-green-50">
                            <td class="p-3 font-medium">{{ $detail->barang->nama_barang ?? 'Barang Dihapus' }}</td>
                            <td class="p-3 text-right">Rp{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="p-3 text-center">{{ $detail->qty }}</td>
                            <td class="p-3 text-right font-semibold">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100 font-extrabold">
                        <td colspan="3" class="p-3 text-right text-lg">GRAND TOTAL</td>
                        <td class="p-3 text-right text-lg text-red-600">Rp{{ number_format($transaksi->total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('kantin.transaksi') }}" class="text-blue-600 hover:underline font-semibold flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Riwayat Transaksi
            </a>
        </div>
    </div>
</body>
</html>