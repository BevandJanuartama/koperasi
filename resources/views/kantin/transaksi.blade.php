<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi Kantin</title>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    {{-- DataTables Buttons CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
</head>
<body class="bg-gray-100 text-gray-800">

<div class="max-w-7xl mx-auto p-6">

    {{-- Judul --}}
    <h1 class="text-3xl font-extrabold mb-8 text-center text-emerald-700">
        Riwayat Transaksi Kantin
    </h1>

    {{-- Tombol Kembali --}}
    <div class="flex justify-end mb-4">
        <a href="{{ route('kantin.kasir') }}"
           class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-600 transition">
            ← Kembali ke Kasir
        </a>
    </div>

    {{-- Filter Tanggal --}}
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-semibold mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date"
                       value="{{ request('start_date', $startDate->toDateString()) }}"
                       class="border rounded-lg px-3 py-2 text-sm focus:ring-emerald-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date"
                       value="{{ request('end_date', $endDate->toDateString()) }}"
                       class="border rounded-lg px-3 py-2 text-sm focus:ring-emerald-500 outline-none">
            </div>

            <button type="submit"
                    class="bg-emerald-500 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-emerald-600 transition">
                Filter
            </button>

            <a href="{{ route('kantin.transaksi') }}"
               class="text-sm text-gray-500 hover:underline">
                Reset
            </a>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- Total Transaksi --}}
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500">Total Penjualan</p>
            <p class="text-3xl font-extrabold text-blue-600">
                {{ $totalTransaksi }} Transaksi
            </p>
            <p class="text-xs text-gray-400 mt-1">
                Periode {{ $startDate->translatedFormat('d M Y') }} -
                {{ $endDate->translatedFormat('d M Y') }}
            </p>
        </div>

        {{-- Total Uang --}}
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-emerald-500">
            <p class="text-sm text-gray-500">Total Uang Masuk</p>
            <p class="text-3xl font-extrabold text-emerald-600">
                Rp{{ number_format($totalUang, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                Periode {{ $startDate->translatedFormat('d M Y') }} -
                {{ $endDate->translatedFormat('d M Y') }}
            </p>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-lg shadow-xl p-6 border-t-4 border-emerald-500 overflow-x-auto">
        <table id="transaksiTable" class="display w-full">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Waktu</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksis as $transaksi)
                <tr>
                    <td></td> {{-- auto nomor --}}
                    <td class="whitespace-nowrap">{{ \Carbon\Carbon::parse($transaksi->created_at)->locale('id')->isoFormat('D MMM Y, HH:mm') }}</td>
                    <td>{{ $transaksi->user->username ?? '-' }}</td>
                    <td class="font-semibold">{{ $transaksi->user->nama ?? '-' }}</td>
                    <td>{{ $transaksi->user->kelas ?? '-' }}</td>
                    <td>{{ $transaksi->user->jurusan ?? '-' }}</td>
                    <td class="font-bold text-emerald-600">
                        Rp{{ number_format($transaksi->total, 0, ',', '.') }}
                    </td>
                    <td>
                        <a href="{{ route('kantin.detail', $transaksi->id) }}"
                        class="text-blue-500 hover:underline font-semibold">
                            Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p class="mt-4 text-sm text-gray-500">
            Menampilkan {{ $transaksis->count() }} transaksi
        </p>
    </div>

</div>

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
{{-- DataTables JS --}}
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
{{-- Buttons JS --}}
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function () {
        let table = $('#transaksiTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '📗 Export Excel',
                    className: '!bg-emerald-500 !text-white !rounded-lg px-4 py-2 hover:!bg-emerald-600 !border-none mb-4',
                    filename: function() {
                        let tglAwal = $('#start_date').val() || 'Awal';
                        let tglAkhir = $('#end_date').val() || 'Akhir';
                        return 'Data Riwayat Transaksi Kantin (' + tglAwal + ' sd ' + tglAkhir + ')';
                    },
                    title: 'RIWAYAT TRANSAKSI KANTIN',
                    exportOptions: {
                        // Export kolom 0 sampai 6 (No, Waktu, NIS, Nama, Kelas, Jurusan, Total)
                        columns: [0, 1, 2, 3, 4, 5, 6],
                        format: {
                            body: function (data, row, column) {
                                // Kolom No otomatis 1, 2, 3...
                                if (column === 0) return row + 1;
                                // Kolom Total (index 6) dibersihkan simbolnya agar jadi angka di Excel
                                if (column === 6) return data.replace(/[^\d]/g, '');
                                return data;
                            }
                        }
                    }
                }
            ],
            pageLength: 10,
            order: [[1, 'desc']],
            columnDefs: [
                {
                    targets: 0,
                    searchable: false,
                    orderable: false
                }
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                },
                zeroRecords: "Data tidak ditemukan"
            }
        });

        // Logika Nomor Urut Otomatis di UI Tabel
        table.on('order.dt search.dt', function () {
            table.column(0, { search: 'applied', order: 'applied' })
                .nodes()
                .each((cell, i) => {
                    cell.innerHTML = i + 1;
                });
        }).draw();
    });
</script>

</body>
</html>