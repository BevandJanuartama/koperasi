<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna - Kantin</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] { display: none !important; }
        /* Style agar input PIN terlihat renggang */
        .pin-input {
            letter-spacing: 0.5em;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 antialiased" x-data="passwordModal()">

    {{-- Logic Alert Sukses dari Laravel --}}
    @if (session('status') === 'password-updated')
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Password Anda telah diperbarui.',
            showConfirmButton: false,
            timer: 2000,
            borderRadius: '1rem'
        });
    </script>
    @endif

    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-emerald-700">Profil</h1>
            </div>
            <button @click="openModal()" 
                class="inline-flex items-center justify-center px-6 py-3 bg-emerald-600 border border-transparent rounded-xl font-bold text-white shadow-lg hover:bg-emerald-700 transition duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                Ubah Password
            </button>
        </div>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden mb-6">
            <div class="p-6 sm:p-8 border-l-8 border-emerald-500">
                <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Informasi Akun Siswa
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Nama Lengkap</label>
                        <p class="mt-1 text-md font-bold text-gray-800">{{ Auth::user()->nama }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Kelas</label>
                        <p class="mt-1 text-md font-bold text-gray-800">{{ Auth::user()->kelas }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Jurusan</label>
                        <p class="mt-1 text-md font-bold text-gray-800">{{ Auth::user()->jurusan }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mt-10">
            {{-- Button Kembali ke Kiri --}}
            <a href="{{ route('siswa.index') }}" 
            class="flex items-center px-6 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-full shadow-sm hover:bg-gray-50 transition duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali
            </a>

            {{-- Button Logout ke Kanan --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                    class="flex items-center px-6 py-2.5 text-sm font-bold text-white bg-red-600 rounded-full shadow-lg hover:bg-red-700 hover:shadow-red-200 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" x2="9" y1="12" y2="12" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>

        <div x-show="step > 0" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            
            <div @click.away="closeModal()" class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-8 relative overflow-hidden">
                
                <div x-show="step === 1" x-transition>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Verifikasi PIN Lama</h3>
                        <p class="text-sm text-gray-500 mb-6">Masukkan 6 angka PIN Anda saat ini.</p>
                    </div>
                    
                    <input type="password" maxlength="6" x-model="oldPass" 
                           @input="handleOldPassInput"
                           :disabled="loading"
                           class="w-full border-gray-300 rounded-2xl p-4 border focus:ring-emerald-500 text-center text-3xl pin-input shadow-inner"
                           placeholder="••••••" autofocus>
                    
                    <div x-show="loading" class="mt-4 text-center">
                        <p class="text-emerald-600 text-xs font-bold animate-bounce">Mengecek...</p>
                    </div>

                    <p x-show="errorMsg" x-text="errorMsg" class="text-red-500 text-xs text-center mt-4 font-bold bg-red-50 p-2 rounded-lg"></p>
                </div>

                <div x-show="step === 2" x-transition>
                    <h3 class="text-xl font-bold text-center text-gray-900 mb-2">Buat PIN Baru</h3>
                    <p class="text-sm text-center text-gray-500 mb-6">Masukkan 6 angka PIN baru.</p>
                    
                    <input type="password" maxlength="6" x-model="newPass"
                           class="w-full border-gray-300 rounded-2xl p-4 border focus:ring-emerald-500 text-center text-3xl pin-input shadow-inner"
                           placeholder="••••••">
                    
                    <div class="flex gap-3 mt-8">
                        <button @click="step = 1; oldPass = ''" class="flex-1 py-3 text-gray-400 font-bold">Kembali</button>
                        <button @click="step = 3" :disabled="newPass.length < 6"
                                class="flex-1 py-3 bg-emerald-600 text-white rounded-xl font-bold disabled:bg-gray-200">Lanjutkan</button>
                    </div>
                </div>

                <div x-show="step === 3" x-transition>
                    <h3 class="text-xl font-bold text-center text-gray-900 mb-2">Konfirmasi PIN</h3>
                    <p class="text-sm text-center text-gray-500 mb-6">Ulangi PIN baru sekali lagi.</p>
                    
                    <input type="password" maxlength="6" x-model="confirmPass"
                           class="w-full border-gray-300 rounded-2xl p-4 border focus:ring-emerald-500 text-center text-3xl pin-input shadow-inner"
                           placeholder="••••••">
                    
                    <p x-show="newPass !== confirmPass && confirmPass.length === 6" class="text-red-500 text-xs text-center mt-4 font-bold">PIN tidak cocok!</p>

                    <div class="flex gap-3 mt-8">
                        <button @click="step = 2" class="flex-1 py-3 text-gray-400 font-bold">Kembali</button>
                        <button @click="submitPassword()" :disabled="newPass !== confirmPass || confirmPass.length < 6 || loading"
                                class="flex-1 py-3 bg-emerald-700 text-white rounded-xl font-bold shadow-lg disabled:bg-gray-200">
                            <span x-show="!loading">Simpan PIN</span>
                            <span x-show="loading">Proses...</span>
                        </button>
                    </div>
                </div>

                <button @click="closeModal()" class="absolute top-4 right-4 text-gray-300 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

    </div>

    <script>
        function passwordModal() {
            return {
                step: 0,
                oldPass: '',
                newPass: '',
                confirmPass: '',
                errorMsg: '',
                loading: false,

                openModal() {
                    this.step = 1;
                    this.oldPass = '';
                    this.newPass = '';
                    this.confirmPass = '';
                    this.errorMsg = '';
                },

                closeModal() {
                    if(!this.loading) this.step = 0;
                },

                handleOldPassInput() {
                    this.errorMsg = '';
                    if (this.oldPass.length === 6) {
                        this.verifyOldPassword();
                    }
                },

                async verifyOldPassword() {
                    this.loading = true;
                    try {
                        const response = await fetch("{{ route('password.check') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ current_password: this.oldPass })
                        });

                        const result = await response.json();

                        if (result.valid) {
                            this.step = 2; // Pindah ke PIN Baru
                        } else {
                            this.errorMsg = "PIN lama Anda salah!";
                            this.oldPass = ''; // Otomatis reset input
                        }
                    } catch (e) {
                        this.errorMsg = "Gagal memverifikasi. Coba lagi.";
                        this.oldPass = '';
                    } finally {
                        this.loading = false;
                    }
                },

                async submitPassword() {
                    this.loading = true;
                    
                    // Membuat form element secara dinamis untuk submit ke Laravel
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('password.update') }}";

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = "{{ csrf_token() }}";

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'PUT';

                    const currentPassword = document.createElement('input');
                    currentPassword.type = 'hidden';
                    currentPassword.name = 'current_password';
                    currentPassword.value = this.oldPass;

                    const password = document.createElement('input');
                    password.type = 'hidden';
                    password.name = 'password';
                    password.value = this.newPass;

                    const passwordConfirmation = document.createElement('input');
                    passwordConfirmation.type = 'hidden';
                    passwordConfirmation.name = 'password_confirmation';
                    passwordConfirmation.value = this.confirmPass;

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    form.appendChild(currentPassword);
                    form.appendChild(password);
                    form.appendChild(passwordConfirmation);

                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }
    </script>
</body>
</html>