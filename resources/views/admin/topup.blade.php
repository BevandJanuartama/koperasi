<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up Saldo - Keren</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Gaya tambahan untuk animasi */
        .card-enter {
            animation: fadeInScale 0.3s ease-out;
        }
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-md transform transition duration-500 hover:shadow-indigo-500/50">
        <h1 class="text-3xl font-extrabold text-indigo-600 mb-8 text-center flex items-center justify-center">
            <svg class="w-8 h-8 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            Top Up Saldo Cepat
        </h1>

        {{-- User info --}}
        <div id="user-info" class="mb-6 hidden p-5 border border-indigo-200 rounded-xl bg-indigo-50 shadow-md card-enter">
            <div class="flex items-center mb-3 border-b pb-2 border-indigo-200">
                <svg class="w-6 h-6 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.51 0 4.847.653 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-lg font-semibold text-indigo-700">Detail Pengguna</h3>
            </div>
            <p class="text-sm text-gray-600 mb-1"><strong>👤 NIS:</strong> <span id="username" class="font-medium text-indigo-800"></span></p>
            <p class="text-sm text-gray-600 mb-1"><strong>📝 Nama:</strong> <span id="nama" class="font-medium text-indigo-800"></span></p>
            <p class="text-sm text-gray-600 mb-1"><strong>📚 Kelas:</strong> <span id="kelas" class="font-medium text-indigo-800"></span></p>
            <p class="text-sm text-gray-600 mb-1"><strong>🎓 Jurusan:</strong> <span id="jurusan" class="font-medium text-indigo-800"></span></p>
            <div class="mt-3 pt-3 border-t border-indigo-200">
                <p class="text-md text-indigo-600 font-bold flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zM9 13h1"></path></svg>
                    Saldo Terakhir: <span class="ml-2">Rp <span id="saldo" class="text-2xl text-yellow-600"></span></span>
                </p>
            </div>
        </div>

        <form action="{{ route('admin.topup.store') }}" method="POST" id="topup-form">
            @csrf
            <input type="hidden" name="card_id" id="card_id">

            <div class="mb-6">
                <label for="saldo_input" class="block text-sm font-semibold text-gray-700 mb-2">Nominal Top Up (Rp)</label>
                <input type="number" id="saldo_input" name="saldo"
                       class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out text-lg"
                       placeholder="Contoh: 50000"
                       min="1"
                       required>
            </div>

            <button type="submit"
                    class="w-full bg-indigo-600 text-white py-3 rounded-lg font-bold text-lg shadow-md hover:bg-indigo-700 transition duration-300 ease-in-out transform hover:scale-[1.01] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Proses Top Up
            </button>
            <p class="mt-4 text-center text-sm text-gray-500">
                ⚠️ **TAP KARTU** untuk memuat data pengguna!
            </p>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputCard = document.getElementById('card_id');
            const userInfoDiv = document.getElementById('user-info');
            const saldoInput = document.getElementById('saldo_input');
            const topupForm = document.getElementById('topup-form');
            let buffer = '';
            let lastTime = Date.now();
            const SCAN_SPEED_THRESHOLD = 120; // Waktu dalam ms antar karakter
            const API_URL = '/admin/topup/user/'; 

            // Hapus fokus otomatis saat halaman dimuat
            document.body.focus();

            // --- Fungsi Notifikasi (Sederhana) ---
            function showNotification(message, isSuccess) {
                const prefix = isSuccess ? '✅ BERHASIL!' : '❌ GAGAL!';
                alert(`${prefix}\n\n${message}`);
            }
            // ------------------------------------

            // --- Event Listener untuk Pembaca Kartu (Keypress) ---
            document.addEventListener('keypress', function(e) {
                const now = Date.now();
                
                if (now - lastTime > SCAN_SPEED_THRESHOLD) {
                    buffer = '';
                }
                lastTime = now;

                if (e.key === 'Enter') {
                    e.preventDefault(); 
                    const cardId = buffer.trim();

                    if(cardId.length > 0) {
                        inputCard.value = cardId;
                        buffer = ''; 
                        
                        userInfoDiv.classList.add('hidden');

                        fetch(API_URL + encodeURIComponent(cardId))
                            .then(res => {
                                if (!res.ok) {
                                    // Penting: Melemparkan error untuk ditangkap di catch block jika HTTP status bukan 2xx
                                    return res.json().then(err => Promise.reject(err));
                                }
                                return res.json();
                            })
                            .then(data => {
                                if(data.success && data.user){
                                    // Data berhasil dimuat
                                    document.getElementById('username').textContent = data.user.username || '-';
                                    document.getElementById('nama').textContent = data.user.nama || '-';
                                    document.getElementById('kelas').textContent = data.user.kelas || '-';
                                    document.getElementById('jurusan').textContent = data.user.jurusan || '-';
                                    const formattedSaldo = parseFloat(data.user.saldo || 0).toLocaleString('id-ID', { minimumFractionDigits: 0 });
                                    document.getElementById('saldo').textContent = formattedSaldo;

                                    userInfoDiv.classList.remove('hidden');
                                    userInfoDiv.classList.add('card-enter');
                                    
                                    // Fokuskan ke input nominal
                                    saldoInput.focus(); 
                                    
                                } else {
                                    // Notifikasi Gagal Memuat Data (jika success: false dari server)
                                    showNotification('User tidak ditemukan atau data tidak valid! Silakan coba lagi.', false);
                                    userInfoDiv.classList.add('hidden');
                                    inputCard.value = ''; 
                                    document.body.focus(); 
                                }
                            })
                            .catch(error => {
                                // Notifikasi Gagal Koneksi/Server (catch jika HTTP error)
                                console.error('Fetch error:', error);
                                showNotification('Terjadi kesalahan saat mengambil data pengguna. Cek koneksi atau URL API.', false);
                                userInfoDiv.classList.add('hidden');
                                inputCard.value = ''; 
                                document.body.focus(); 
                            });
                    }
                    buffer = ''; 
                } else {
                    if (e.key.length === 1) {
                         buffer += e.key;
                    }
                }
            });

            // --- Logika Submission Form (Notifikasi Top Up) ---
            topupForm.addEventListener('submit', function(e) {
                e.preventDefault(); 

                if (!inputCard.value) {
                    showNotification('Silakan tap kartu RFID untuk memuat data pengguna terlebih dahulu.', false);
                    document.body.focus();
                    return;
                }
                
                const nominal = parseFloat(saldoInput.value);

                if (nominal <= 0 || isNaN(nominal)) {
                    showNotification('Nominal top up harus lebih besar dari Rp 0.', false);
                    saldoInput.focus();
                    return;
                }
                
                const formData = new FormData(topupForm);

                fetch(topupForm.action, {
                    method: 'POST',
                    body: formData,
                })
                .then(res => {
                    if (!res.ok) {
                        // Jika status HTTP bukan 2xx (misalnya 500, yang bisa terjadi jika saldo bertambah tapi ada error setelahnya)
                        return res.json().then(err => Promise.reject(err));
                    }
                    return res.json();
                })
                .then(data => {
                    // Cek respons dari Laravel. Jika server mengembalikan success:true, dianggap berhasil.
                    if(data.success){
                        // ✅ Notifikasi BERHASIL TOP UP
                        showNotification(`Top Up Rp ${nominal.toLocaleString('id-ID')} berhasil untuk ${data.user.nama}! Saldo baru: Rp ${parseFloat(data.user.saldo).toLocaleString('id-ID')}`, true);
                        
                        // Reset tampilan
                        topupForm.reset();
                        userInfoDiv.classList.add('hidden');
                        inputCard.value = '';
                        document.body.focus(); 
                    } else {
                        // ❌ Notifikasi GAGAL TOP UP (Server bilang gagal, walau saldo bertambah di database)
                        showNotification(`Gagal melakukan Top Up: ${data.message || 'Terjadi kesalahan pada server. (Kode: Sukses Database/Gagal Respons)'}`, false);
                        saldoInput.focus();
                    }
                })
                .catch(error => {
                    // ❌ Notifikasi GAGAL TOP UP (Kesalahan Jaringan atau Server Error HTTP)
                    console.error('Submit error:', error);
                    let errorMessage = 'Terjadi kesalahan Jaringan/Server. Top Up mungkin tidak tercatat. Coba lagi.';
                    if (error.errors) {
                        errorMessage = 'Validasi Gagal:\n' + Object.values(error.errors).flat().join('\n');
                    }
                    showNotification(errorMessage, false);
                    saldoInput.focus();
                });
            });
        });
    </script>
</body>
</html>