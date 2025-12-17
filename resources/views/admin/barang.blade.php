<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        .dataTables_wrapper .dataTables_filter input, 
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            padding: 0.5rem;
            outline: none;
        }
        .dataTables_wrapper .dataTables_filter { margin-bottom: 1.5rem; }
        /* Style untuk kolom nomor agar di tengah */
        .text-center-col { text-align: center; }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold text-emerald-600 mb-6 flex items-center">
            <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 11v10"></path></svg>
            Kelola Data Barang
        </h1>

        {{-- NOTIFIKASI --}}
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        {{-- FORM TAMBAH BARANG --}}
        <div class="bg-white p-6 rounded-xl shadow-lg mb-8">
            <h2 class="text-xl font-semibold text-emerald-700 mb-4 border-b pb-2">➕ Tambah Barang Baru</h2>
            <form action="{{ route('admin.barang.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kode Barcode</label>
                        <input type="text" id="kode" name="kode" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-emerald-500 font-mono" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                        <input type="text" name="nama_barang" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-emerald-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                        <input type="number" name="harga" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-emerald-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stok</label>
                        <input type="number" name="stok" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-emerald-500" required>
                    </div>
                </div>
                <button type="submit" class="mt-6 w-full bg-emerald-600 text-white py-2 rounded-lg font-semibold hover:bg-emerald-700 transition">Simpan Barang</button>
            </form>
        </div>
        
        {{-- TABEL DATA --}}
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">📊 Daftar Semua Barang</h2>
            
            <table id="barangTable" class="w-full border border-gray-200">
                <thead class="bg-emerald-600 text-white">
                    <tr>
                        <th class="px-4 py-3 text-center text-sm font-semibold w-10">No</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Barcode</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Nama Barang</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Harga</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Stok</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($barang as $b)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 text-sm text-center font-medium text-gray-500"></td> <td class="px-4 py-4 text-sm text-gray-700 font-mono">{{ $b->kode }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900 font-bold">{{ $b->nama_barang }}</td>
                        <td class="px-4 py-4 text-sm text-gray-700 font-semibold text-emerald-600">Rp{{ number_format($b->harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-4 text-sm text-gray-900 font-medium">{{ $b->stok }}</td>
                        <td class="px-4 py-4 text-sm">
                            <button onclick="openEditModal({{ $b->id }}, '{{ $b->kode }}', '{{ $b->nama_barang }}', {{ $b->harga }}, {{ $b->stok }})" 
                                    class="text-blue-600 hover:underline font-bold mr-3">Edit</button>
                            
                            <form action="{{ route('admin.barang.destroy', $b->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus barang ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline font-bold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL EDIT (Sama seperti sebelumnya) --}}
    <script>
        $(document).ready(function() {
            var t = $('#barangTable').DataTable({
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0 // Kolom nomor tidak bisa di-sort/cari
                } ],
                "order": [[ 2, 'asc' ]], // Default urut berdasarkan nama barang
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                }
            });

            // Fungsi untuk membuat nomor urut dinamis
            t.on('order.dt search.dt', function () {
                t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            }).draw();

            $('#kode').focus();
        });

        // ... (Fungsi Modal openEditModal & closeEditModal sama seperti sebelumnya) ...
    </script>
</body>
</html>