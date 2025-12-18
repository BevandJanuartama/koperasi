<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Kartu</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    /* Membuat jarak antar titik password agar lebih estetik */
    .pin-input {
      letter-spacing: 0.5em;
    }
    .pin-input::placeholder {
      letter-spacing: normal;
      font-size: 1.5rem;
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center py-10">

  <div class="w-full max-w-lg bg-white shadow-lg rounded-2xl p-8">
    <h1 class="text-3xl font-bold text-emerald-600 mb-6 text-center">💳 Registrasi Kartu Baru</h1>

    @if(session('success'))
      <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">
        {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4">
        <ul class="list-disc ml-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="mb-8 p-4 border-2 border-dashed border-gray-200 rounded-xl">
        <h2 class="text-sm font-bold text-gray-600 mb-3 uppercase tracking-wider">Bulk Import (Excel)</h2>
        <form action="{{ route('admin.importExcel') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
            @csrf
            <input type="file" name="file_excel" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" required>
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-black transition">
                Import
            </button>
        </form>
        <p class="text-[10px] text-gray-400 mt-2 italic">*Pastikan header Excel sesuai: username, nama, kelas, jurusan, saldo, level, password, card_id</p>
    </div>

    <hr class="mb-8">

    <form action="{{ route('admin.storeCard') }}" method="POST" class="space-y-5">
      @csrf

      <div>
        <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Username (NIS)</label>
        <input type="text" id="username" name="username" value="{{ old('username') }}"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
      </div>

      <div>
        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
        <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="kelas" class="block text-sm font-semibold text-gray-700 mb-1">Kelas</label>
          <input type="text" id="kelas" name="kelas" value="{{ old('kelas') }}"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
        </div>

        <div>
          <label for="jurusan" class="block text-sm font-semibold text-gray-700 mb-1">Jurusan</label>
          <input type="text" id="jurusan" name="jurusan" value="{{ old('jurusan') }}"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
        </div>
      </div>

      <div>
        <label for="card_id" class="block text-sm font-semibold text-gray-700 mb-1">ID Kartu</label>
        <input type="text" id="card_id" name="card_id" value="{{ old('card_id') }}"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
      </div>

      <div>
          <label for="password" class="block text-sm font-semibold text-gray-700 mb-2 text-center">PIN Registrasi (6 Angka)</label>
          <input 
              type="password" 
              id="password" 
              name="password"
              inputmode="numeric" 
              pattern="[0-9]*" 
              maxlength="6"
              oninput="this.value = this.value.replace(/[^0-9]/g, '');"
              class="w-full border-gray-300 rounded-2xl p-4 border focus:ring-2 focus:ring-emerald-500 focus:outline-none text-center text-3xl tracking-[1em] shadow-inner bg-gray-50"
              placeholder="••••••" 
              required
          >
          <p class="text-center text-xs text-gray-400 mt-2">Gunakan 6 digit angka rahasia</p>
      </div>

      <div>
        <label for="level" class="block text-sm font-semibold text-gray-700 mb-1">Level Pengguna</label>
        <select id="level" name="level"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
          <option value="">-- Pilih Level --</option>
          <option value="user">siswa</option>
          <option value="admin">admin</option>
          <option value="kantin">kantin</option>
        </select>
      </div>

      <button type="submit"
        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 rounded-lg transition">
        Simpan Registrasi
      </button>
    </form>

    <div class="text-center mt-6">
      <a href="{{ url('/admin/index') }}" class="text-emerald-600 hover:underline">← Kembali ke Dashboard</a>
    </div>
  </div>

</body>
</html>
