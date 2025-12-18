<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <style>
        .dataTables_wrapper .dataTables_filter input, 
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            padding: 0.5rem;
            outline: none;
        }
        .dataTables_wrapper .dataTables_filter { margin-bottom: 1.5rem; }
        .text-center-col { text-align: center; }
        
        /* Style Tombol Export Emerald */
        .dt-buttons { margin-bottom: 15px; gap: 8px; display: flex; }
        button.dt-button {
            background: #10b981 !important; /* emerald-500 */
            color: white !important;
            border: none !important;
            border-radius: 0.5rem !important;
            padding: 0.6rem 1.2rem !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            transition: all 0.2s;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05) !important;
        }
        button.dt-button:hover {
            background: #059669 !important; /* emerald-600 */
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold text-emerald-600 mb-6 flex items-center">
            <svg class="w-8 h-8 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 11v10"></path></svg>
            Kelola Data Barang
        </h1>

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

        <div class="bg-white p-6 rounded-xl shadow-md mb-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4">Import Data Barang</h2>
            <form action="{{ route('admin.barang.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-4">
                @csrf
                <input type="file" name="file_barang" class="border p-2 rounded-lg text-sm w-full" required>
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg hover:bg-emerald-700 transition">
                    Upload
                </button>
            </form>
            <p class="text-xs text-gray-500 mt-2 italic">
                *Pastikan head Excel: <b>nama_barang, harga, kode, stok</b>
            </p>
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
                        <td class="px-4 py-4 text-sm text-center font-medium text-gray-500"></td> 
                        <td class="px-4 py-4 text-sm text-gray-700 font-mono">{{ $b->kode }}</td>
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

    <script>
        $(document).ready(function() {
            // Fungsi untuk mendapatkan tanggal hari ini format ddd-mm-yyyy
            var d = new Date();
            var dateStr = ("0" + d.getDate()).slice(-2) + "-" + ("0" + (d.getMonth() + 1)).slice(-2) + "-" + d.getFullYear();
            var fileName = 'data barang kantin - ' + dateStr;

            var t = $('#barangTable').DataTable({
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        extend: 'excelHtml5',
                        text: '📗 Export Excel',
                        filename: fileName,
                        title: 'DAFTAR DATA BARANG KANTIN',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4],
                            format: {
                                body: function (data, row, column, node) {
                                    // Kolom 0: Mengisi nomor urut
                                    if (column === 0) {
                                        return row + 1;
                                    }
                                    
                                    // Kolom 3: Kolom Harga (Menghapus 'Rp' dan '.')
                                    if (column === 3) {
                                        // Mengambil teks asli dari cell, hapus Rp, hapus titik
                                        // Menggunakan regex untuk menghapus semua karakter kecuali angka
                                        return data.replace(/[^\d]/g, '');
                                    }
                                    
                                    return data;
                                }
                            }
                        }
                    },
                ],
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0 
                } ],
                "order": [[ 2, 'asc' ]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                }
            });

            // Nomor urut dinamis di tampilan web
            t.on('order.dt search.dt', function () {
                t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            }).draw();

            $('#kode').focus();
        });
    </script>
</body>
</html>