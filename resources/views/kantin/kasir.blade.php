<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Kantin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .animate-pulse {
            animation: pulse 1s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }
        @keyframes fade-in {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="max-w-5xl mx-auto p-6">
        <h1 class="text-3xl font-extrabold mb-6 text-center text-emerald-700">POS Kantin Sekolah</h1>

        {{-- Scan Input Universal --}}
        <div class="bg-white rounded-lg shadow-xl p-6 mb-6 border-t-4 border-emerald-500 relative">
            <h2 class="text-xl font-bold mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Scanner & Cari Barang
            </h2>
            <div class="flex items-center space-x-4">
                <div class="relative flex-grow">
                    <input type="text" name="scan_input" id="scan_input"
                        placeholder="Ketik Nama Barang atau Scan Barcode..."
                        class="border rounded-lg p-3 w-full focus:outline-none focus:ring-2 focus:ring-emerald-400 text-lg"
                        autocomplete="off"
                        autofocus>
                    
                    {{-- Dropdown Hasil Pencarian --}}
                    <div id="search_results" class="absolute z-[100] w-full bg-white shadow-2xl rounded-b-lg border border-gray-200 mt-1 hidden max-h-60 overflow-y-auto">
                    </div>
                </div>
                <button type="button" class="bg-emerald-500 text-white px-6 py-3 rounded-lg font-semibold" disabled>Scan Otomatis</button>
            </div>
            <p class="mt-2 text-sm text-gray-500">Ketik minimal 2 karakter untuk mencari, atau Enter untuk scan barcode langsung.</p>
        </div>

        {{-- Keranjang Belanja --}}
        <div class="bg-white rounded-lg shadow-xl p-6 mb-6">
            <h2 class="text-xl font-bold mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l3 9l12 0l-3-9H8L5 3z"></path></svg>
                Keranjang Belanja (<span id="keranjangCount">{{ count($keranjang) }}</span> Item)
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left rounded-lg overflow-hidden">
                    <thead class="bg-gray-200 uppercase text-sm">
                        <tr>
                            <th class="p-3 border border-gray-300">No</th>
                            <th class="p-3 border border-gray-300">Barang</th>
                            <th class="p-3 border border-gray-300 text-right">Harga Satuan</th>
                            <th class="p-3 border border-gray-300 text-center w-1/5">Qty</th>
                            <th class="p-3 border border-gray-300 text-right">Subtotal</th>
                            <th class="p-3 border border-gray-300 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="keranjangTableBody">
                        @forelse ($keranjang as $item)
                            <tr id="row-{{ $item['id'] }}" class="hover:bg-gray-50 border-b">
                                <td class="p-3 index-col">{{ $loop->iteration }}</td>
                                <td class="p-3 font-medium">{{ $item['nama_barang'] }}</td>
                                <td class="p-3 text-right">Rp{{ number_format($item['harga_satuan'], 0, ',', '.') }}</td>
                                <td class="p-3 text-center">
                                    <div class="flex justify-center items-center space-x-2">
                                        <button type="button" data-id="{{ $item['id'] }}" data-action="decrease" class="qty-btn bg-red-400 text-white rounded-full w-6 h-6 leading-none text-xl font-bold hover:bg-red-500 transition disabled:opacity-50" @if($item['qty'] <= 1) disabled @endif>-</button>
                                        <span id="qty-{{ $item['id'] }}" class="font-semibold w-8 text-center">{{ $item['qty'] }}</span>
                                        <button type="button" data-id="{{ $item['id'] }}" data-action="increase" class="qty-btn bg-green-400 text-white rounded-full w-6 h-6 leading-none text-xl font-bold hover:bg-green-500 transition">+</button>
                                    </div>
                                </td>
                                <td class="p-3 text-right font-semibold subtotal-col" id="subtotal-{{ $item['id'] }}">
                                    Rp{{ number_format($item['subtotal'], 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-center">
                                    <button type="button" data-id="{{ $item['id'] }}" class="hapus-btn text-red-500 hover:text-red-700 transition">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="6" class="p-6 text-center text-gray-500 text-lg">
                                    Keranjang kosong. Silakan scan barang!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Total dan Bayar --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h3 class="text-2xl font-extrabold text-blue-800 mb-4 sm:mb-0">
                    TOTAL BELANJA: <span id="grandTotalDisplay">Rp{{ number_format($total, 0, ',', '.') }}</span>
                </h3>

                <button id="bayarTriggerButton"
                    @if ($total == 0) disabled @endif
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-bold disabled:bg-gray-400 disabled:cursor-not-allowed text-lg w-full sm:w-auto">
                    Siap Bayar
                </button>
            </div>
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('kantin.transaksi') }}" class="text-blue-600 hover:underline font-semibold">
                Liat Riwayat Transaksi
            </a>
        </div>
    </div>

    <script>
        // === KONSTANTA & DOM ELEMENTS ===
        const SCAN_INPUT = document.getElementById('scan_input');
        const KERANJANG_BODY = document.getElementById('keranjangTableBody');
        const GRAND_TOTAL_DISPLAY = document.getElementById('grandTotalDisplay');
        const KERANJANG_COUNT = document.getElementById('keranjangCount');
        const BAYAR_TRIGGER_BUTTON = document.getElementById('bayarTriggerButton');
        const SEARCH_RESULTS = document.getElementById('search_results');
        const CSRF_TOKEN = '{{ csrf_token() }}';
        
        let isPaymentMode = false;
        let searchTimeout = null;

        // === UTILITY: FORMAT RUPIAH ===
        // Fungsi ini memastikan angka murni dari server diubah menjadi format titik (e.g., 3000 -> 3.000)
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // === NOTIFIKASI POPUP ===
        function showPaymentPopup(type, message) {
            const popup = document.createElement('div');
            popup.className = 'fixed inset-0 flex items-center justify-center z-50 bg-black/40 backdrop-blur-sm transition';
            popup.innerHTML = `
                <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full text-center animate-fade-in">
                    <div class="text-7xl mb-4">${type === 'success' ? '✅' : '❌'}</div>
                    <p class="text-2xl font-bold text-gray-800 mb-2">${type === 'success' ? 'BERHASIL' : 'GAGAL'}</p>
                    <p class="text-lg text-gray-600 mb-6">${message}</p>
                    <button class="mt-2 px-8 py-3 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 transition text-lg" onclick="this.closest('.fixed').remove()">OK</button>
                </div>
            `;
            document.body.appendChild(popup);
            setTimeout(() => popup.remove(), 5000);
        }

        // === AJAX UTILITY ===
        async function sendAjaxRequest(url, method, data) {
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                return { ...result, ok: response.ok };
            } catch (error) {
                return { ok: false, success: false, message: "Koneksi ke server terputus." };
            } finally {
                if(!searchTimeout) { 
                    SCAN_INPUT.value = ''; 
                    SCAN_INPUT.focus(); 
                }
            }
        }

        // === RENDER LOGIC ===
        function updateGrandTotal(total) {
            // total yang diterima dari server sudah diformat string (e.g. "3.000")
            GRAND_TOTAL_DISPLAY.textContent = 'Rp' + total;
            
            const numericTotal = parseInt(total.toString().replace(/\./g, ''));
            if (!isPaymentMode) BAYAR_TRIGGER_BUTTON.disabled = numericTotal === 0;
        }

        function reindexTable() {
            const rows = KERANJANG_BODY.querySelectorAll('tr[id^="row-"]');
            const emptyRow = document.getElementById('emptyRow');
            
            if (rows.length > 0 && emptyRow) emptyRow.remove();
            
            rows.forEach((row, index) => {
                const col = row.querySelector('.index-col');
                if (col) col.textContent = index + 1;
            });
            KERANJANG_COUNT.textContent = rows.length;

            if (rows.length === 0 && !document.getElementById('emptyRow')) {
                KERANJANG_BODY.innerHTML = '<tr id="emptyRow"><td colspan="6" class="p-6 text-center text-gray-500 text-lg">Keranjang kosong. Silakan scan barang!</td></tr>';
                BAYAR_TRIGGER_BUTTON.disabled = true;
            }
        }

        // === LIVE SEARCH LOGIC ===
        SCAN_INPUT.addEventListener('input', function() {
            if (isPaymentMode) return;
            const query = this.value.trim();
            if (query.length < 2) { SEARCH_RESULTS.classList.add('hidden'); return; }

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(async () => {
                try {
                    const res = await fetch(`{{ route('kantin.search') }}?q=${query}`);
                    if (res.ok) {
                        const data = await res.json();
                        renderSearchDropdown(data);
                    }
                } catch (e) { console.error("Search error"); }
                searchTimeout = null;
            }, 300);
        });

        function renderSearchDropdown(items) {
            if (items.length === 0) {
                SEARCH_RESULTS.innerHTML = '<div class="p-3 text-gray-500 text-sm">Barang tidak ditemukan.</div>';
            } else {
                SEARCH_RESULTS.innerHTML = items.map(item => {
                    const isHabis = item.stok <= 0;
                    return `
                        <div class="p-3 border-b flex justify-between items-center transition ${isHabis ? 'bg-gray-100 cursor-not-allowed opacity-60' : 'hover:bg-emerald-50 cursor-pointer'}" 
                            onclick="${isHabis ? `showPaymentPopup('error', 'Stok ${item.nama_barang} habis!')` : `selectProduct('${item.kode}')`}">
                            <div>
                                <div class="font-bold ${isHabis ? 'text-gray-500' : 'text-gray-700'} text-sm">
                                    ${item.nama_barang} ${isHabis ? '<span class="text-red-500 text-xs">[STOK HABIS]</span>' : ''}
                                </div>
                                <div class="text-xs text-gray-400">Kode: ${item.kode} | Stok: ${item.stok}</div>
                            </div>
                            <div class="${isHabis ? 'text-gray-400' : 'text-emerald-600'} font-bold text-sm">
                                Rp${formatRupiah(item.harga)}
                            </div>
                        </div>
                    `;
                }).join('');
            }
            SEARCH_RESULTS.classList.remove('hidden');
        }

        function selectProduct(kode) {
            SEARCH_RESULTS.classList.add('hidden');
            SCAN_INPUT.value = '';
            handleScan(kode);
        }

        // === SCAN HANDLER ===
        async function handleScan(kode) {
            const res = await sendAjaxRequest('{{ route("kantin.scan") }}', 'POST', { kode_barang: kode });
            if (res.success) {
                const item = res.item;
                const row = document.getElementById(`row-${item.id}`);
                if (row) {
                    document.getElementById(`qty-${item.id}`).textContent = item.qty;
                    // Pastikan subtotal diformat ulang dengan titik
                    document.getElementById(`subtotal-${item.id}`).textContent = 'Rp' + formatRupiah(item.subtotal.toString().replace(/\./g, ''));
                    row.querySelector('.qty-btn[data-action="decrease"]').disabled = item.qty <= 1;
                } else {
                    const html = `
                        <tr id="row-${item.id}" class="hover:bg-gray-50 border-b">
                            <td class="p-3 index-col"></td>
                            <td class="p-3 font-medium text-sm text-gray-700">${item.nama_barang}</td>
                            <td class="p-3 text-right text-sm">Rp${formatRupiah(item.harga_satuan)}</td>
                            <td class="p-3 text-center">
                                <div class="flex justify-center items-center space-x-2">
                                    <button type="button" data-id="${item.id}" data-action="decrease" class="qty-btn bg-red-400 text-white rounded-full w-6 h-6 flex items-center justify-center font-bold hover:bg-red-500 transition disabled:opacity-50" ${item.qty <= 1 ? 'disabled' : ''}>-</button>
                                    <span id="qty-${item.id}" class="font-semibold w-8 text-center text-sm">${item.qty}</span>
                                    <button type="button" data-id="${item.id}" data-action="increase" class="qty-btn bg-green-400 text-white rounded-full w-6 h-6 flex items-center justify-center font-bold hover:bg-green-500 transition">+</button>
                                </div>
                            </td>
                            <td class="p-3 text-right font-semibold text-sm text-blue-600" id="subtotal-${item.id}">Rp${formatRupiah(item.subtotal)}</td>
                            <td class="p-3 text-center"><button type="button" data-id="${item.id}" class="hapus-btn text-red-500 hover:text-red-700 text-sm">Hapus</button></td>
                        </tr>`;
                    KERANJANG_BODY.insertAdjacentHTML('beforeend', html);
                }
                updateGrandTotal(res.total);
                reindexTable();
            } else {
                showPaymentPopup('error', res.message);
            }
        }

        // === EVENT DELEGATION (Qty & Hapus) ===
        KERANJANG_BODY.addEventListener('click', async (e) => {
            const target = e.target;
            
            // Qty Change
            const qBtn = target.closest('.qty-btn');
            if (qBtn) {
                const id = qBtn.dataset.id;
                const act = qBtn.dataset.action;
                const cur = parseInt(document.getElementById(`qty-${id}`).textContent);
                const next = act === 'increase' ? cur + 1 : cur - 1;
                
                if (next > 0) {
                    const res = await sendAjaxRequest('{{ route("kantin.qty.update") }}', 'POST', { id, qty: next });
                    if (res.success) {
                        document.getElementById(`qty-${id}`).textContent = res.newQty;
                        // Format subtotal agar titik muncul tanpa refresh
                        document.getElementById(`subtotal-${id}`).textContent = 'Rp' + formatRupiah(res.subtotal.toString().replace(/\./g, ''));
                        document.querySelector(`#row-${id} .qty-btn[data-action="decrease"]`).disabled = res.newQty <= 1;
                        updateGrandTotal(res.total);
                    } else {
                        showPaymentPopup('error', res.message);
                    }
                }
                return;
            }

            // Hapus Item
            const hBtn = target.closest('.hapus-btn');
            if (hBtn) {
                const id = hBtn.dataset.id;
                const res = await sendAjaxRequest('{{ route("kantin.hapus", ["id" => ":id"]) }}'.replace(':id', id), 'DELETE', {});
                if (res.success) { 
                    document.getElementById(`row-${id}`).remove(); 
                    updateGrandTotal(res.total); 
                    reindexTable(); 
                }
            }
        });

        // === SCAN INPUT KEYDOWN ===
        SCAN_INPUT.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                const val = SCAN_INPUT.value.trim();
                if (!val) return;
                isPaymentMode ? handlePayment(val) : handleScan(val);
                SCAN_INPUT.value = '';
            }
        });

        // === PAYMENT LOGIC ===
        async function handlePayment(kartu) {
            const res = await sendAjaxRequest('{{ route("kantin.bayar") }}', 'POST', { user_kartu: kartu });
            if (res.success) {
                showPaymentPopup('success', res.message);
                KERANJANG_BODY.innerHTML = ''; 
                updateGrandTotal('0'); 
                reindexTable();
            } else { 
                showPaymentPopup('error', res.message); 
            }
            disablePaymentMode();
        }

        function enablePaymentMode() {
            if (parseInt(KERANJANG_COUNT.textContent) === 0) return;
            isPaymentMode = true;
            BAYAR_TRIGGER_BUTTON.textContent = 'SCAN KARTU SEKARANG!';
            BAYAR_TRIGGER_BUTTON.classList.replace('bg-blue-600', 'bg-orange-500');
            BAYAR_TRIGGER_BUTTON.classList.add('animate-pulse');
            SCAN_INPUT.placeholder = 'SCAN KARTU...';
            SCAN_INPUT.focus();
        }

        function disablePaymentMode() {
            isPaymentMode = false;
            BAYAR_TRIGGER_BUTTON.textContent = 'Siap Bayar';
            BAYAR_TRIGGER_BUTTON.classList.replace('bg-orange-500', 'bg-blue-600');
            BAYAR_TRIGGER_BUTTON.classList.remove('animate-pulse');
            SCAN_INPUT.placeholder = 'Ketik Nama Barang atau Scan Barcode...';
            reindexTable();
        }

        // === INITIALIZATION ===
        BAYAR_TRIGGER_BUTTON.addEventListener('click', () => isPaymentMode ? disablePaymentMode() : enablePaymentMode());
        
        document.addEventListener('click', (e) => { 
            if (!SCAN_INPUT.contains(e.target) && !SEARCH_RESULTS.contains(e.target)) {
                SEARCH_RESULTS.classList.add('hidden'); 
            }
        });

        document.addEventListener('DOMContentLoaded', reindexTable);
    </script>
</body>
</html>