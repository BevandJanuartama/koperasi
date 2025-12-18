<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, WithMapping
{
    /**
     * Fungsi ini berjalan SEBELUM validasi.
     * Kita bersihkan data password di sini.
     */
    public function map($row): array
    {
        // 1. Hilangkan ".0" jika Excel membacanya sebagai desimal
        $pass = str_replace('.0', '', (string)$row['password']);
        
        // 2. Tambahkan kembali nol di depan jika hilang (paksa jadi 6 digit)
        // Ini akan mengubah "123" menjadi "000123"
        $row['password'] = str_pad($pass, 6, '0', STR_PAD_LEFT);

        return $row;
    }

    public function model(array $row)
    {
        return new User([
            'username' => (string) $row['username'],
            'nama'     => trim($row['nama']),
            'kelas'    => trim($row['kelas']),
            'jurusan'  => trim($row['jurusan']),
            'saldo'    => (int) ($row['saldo'] ?? 0),
            'level'    => strtolower(trim($row['level'])),
            'card_id'  => (string) $row['card_id'],
            'password' => $row['password'], // Jangan di-Hash di sini jika di Model sudah ada casts 'hashed'
        ]);
    }

    public function rules(): array
    {
        return [
            '*.username' => 'required|unique:users,username',
            '*.card_id'  => 'required|unique:users,card_id',
            '*.password' => 'required|digits:6', // Gunakan digits:6 agar lebih simpel
            '*.level'    => 'required|in:user,admin,kantin',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.password.digits' => 'Password wajib berupa 6 digit angka.',
            '*.username.unique' => 'Username :input sudah terdaftar.',
            '*.card_id.unique'  => 'Card ID sudah terdaftar',
        ];
    }
}