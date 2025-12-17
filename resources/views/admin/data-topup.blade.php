<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Top Up</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
  <h1 class="text-3xl font-bold text-emerald-600 mb-4">📊 Riwayat Top Up</h1>

  <table class="min-w-full bg-white rounded-xl shadow-md overflow-hidden">
    <thead class="bg-emerald-600 text-white">
      <tr>
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
        <td class="px-6 py-4">{{ $t->username }}</td>
        <td class="px-6 py-4">{{ $t->user_name }}</td>
        <td class="px-6 py-4">{{ $t->kelas }}</td>
        <td class="px-6 py-4">{{ $t->jurusan }}</td>
        <td class="px-6 py-4">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
        <td class="px-6 py-4">{{ $t->created_at }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
