<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Kartu</title>
  <script src="https://cdn.tailwindcss.com"></script>
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

    <form action="{{ url('/admin/store-card') }}" method="POST" class="space-y-5">
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
        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
        <input type="password" id="password" name="password"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
      </div>

      <div>
        <label for="level" class="block text-sm font-semibold text-gray-700 mb-1">Level Pengguna</label>
        <select id="level" name="level"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none" required>
          <option value="">-- Pilih Level --</option>
          <option value="user">siswa</option>
          <option value="admin">admin</option>
          <option value="admin">kantin</option>
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
