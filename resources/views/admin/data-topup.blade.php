<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Top Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">

    <style>
        /* Custom styling agar selaras dengan Tailwind */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            padding: 0.5rem;
            margin-bottom: 1rem;
        }
        .dt-buttons { margin-bottom: 1rem; gap: 0.5rem; display: flex; }
        button.dt-button {
            background: #10b981 !important;
            color: white !important;
            border: none !important;
            border-radius: 0.5rem !important;
            padding: 0.5rem 1rem !important;
            font-weight: 600 !important;
        }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-emerald-600 mb-6">📊 Riwayat Top Up</h1>

        {{-- Filter Tanggal --}}
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-semibold mb-1">Dari Tanggal</label>
                    <input type="date" name="min"
                          value="{{ request('start_date', $startDate->toDateString()) }}"
                          class="border rounded-lg px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Sampai Tanggal</label>
                    <input type="date" name="max"
                          value="{{ request('end_date', $endDate->toDateString()) }}"
                          class="border rounded-lg px-3 py-2 text-sm">
                </div>

                <button type="submit"
                        class="bg-emerald-500 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-emerald-600 transition">
                    Filter
                </button>

                <a href="{{ route('admin.data-topup') }}"
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
                {{ $totalTopupCount }} Transaksi
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
                Rp{{ number_format($totalUangTopup, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                Periode {{ $startDate->translatedFormat('d M Y') }} -
                {{ $endDate->translatedFormat('d M Y') }}
            </p>
        </div>
    </div>

        {{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div class="bg-white p-6 rounded-xl shadow-md border-l-8 border-emerald-500">
              <div class="flex items-center">
                  <div class="p-3 bg-emerald-100 rounded-full">
                      <span class="text-2xl">💰</span>
                  </div>
                  <div class="ml-4">
                      <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Nominal Top Up</p>
                      <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalUangTopup, 0, ',', '.') }}</p>
                  </div>
              </div>
          </div>

          <div class="bg-white p-6 rounded-xl shadow-md border-l-8 border-blue-500">
              <div class="flex items-center">
                  <div class="p-3 bg-blue-100 rounded-full">
                      <span class="text-2xl">📝</span>
                  </div>
                  <div class="ml-4">
                      <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Transaksi</p>
                      <p class="text-2xl font-bold text-gray-800">{{ number_format($totalTopupCount, 0, ',', '.') }} Kali</p>
                  </div>
              </div>
          </div>
      </div> --}}

        <div class="bg-white p-6 rounded-xl shadow-md">
            <table id="topupTable" class="min-w-full display">
                <thead class="bg-emerald-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">No</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">NIS</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Nama Pengguna</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Kelas</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Jurusan</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Nominal</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($topup as $t)
                    <tr>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4">{{ $t->username }}</td>
                        <td class="px-6 py-4 font-bold">{{ $t->user_name }}</td>
                        <td class="px-6 py-4">{{ $t->kelas }}</td>
                        <td class="px-6 py-4">{{ $t->jurusan }}</td>
                        <td class="px-6 py-4 text-emerald-600 font-semibold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($t->created_at)->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>
    
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <script>
        // Custom Filter Logic untuk DataTables
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            var min = minDate.val();
            var max = maxDate.val();
            var date = new Date(data[5]); // Kolom ke-5 adalah Tanggal

            if (
                (min === null && max === null) ||
                (min === null && date <= max) ||
                (min <= date && max === null) ||
                (min <= date && date <= max)
            ) {
                return true;
            }
            return false;
        });

        $(document).ready(function() {
            // Inisialisasi DatePicker
            minDate = new DateTime($('#min'), { format: 'YYYY-MM-DD' });
            maxDate = new DateTime($('#max'), { format: 'YYYY-MM-DD' });

            var table = $('#topupTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                    extend: 'excelHtml5',
                    text: '📗 Export Excel',
                    filename: function() {
                        // Ambil nilai dari input date
                        var tglAwal = $('#min').val(); 
                        var tglAkhir = $('#max').val();

                        // Jika input kosong (jarang terjadi karena ada default dari controller), 
                        // berikan fallback tanggal hari ini
                        if (!tglAwal) tglAwal = new Date().toISOString().split('T')[0];
                        if (!tglAkhir) tglAkhir = new Date().toISOString().split('T')[0];

                        return 'Data Topup (' + tglAwal + ' s.d ' + tglAkhir + ')';
                    },
                    title: 'Data Riwayat Top Up Kantin',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6],
                        format: {
                            body: function (data, row, column, node) {
                                // 1. Kolom 0 (No): Isi nomor urut otomatis
                                if (column === 0) {
                                    return row + 1;
                                }
                                
                                // 2. Kolom 5 (Nominal): Hapus Rp dan titik agar terbaca angka
                                // Catatan: Sebelumnya Anda menggunakan index 4, padahal index 4 adalah JURUSAN
                                if (column === 5) {
                                    return data.replace(/[^\d]/g, '');
                                }
                                
                                // Selain itu, kembalikan data asli (termasuk Jurusan di index 4)
                                return data;
                            }
                        }
                    }
                },
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                }
            });

            // --- LOGIKA NOMOR OTOMATIS ---
            table.on('draw.dt', function () {
                var info = table.page.info();
                table.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1 + info.start;
                });
            });

            // Refresh tabel saat input tanggal berubah
            $('#min, #max').on('change', function () {
                table.draw();
            });
        });
    </script>
</body>
</html>