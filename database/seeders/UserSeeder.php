<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $users = [
            [
                'id' => 1,
                'username' => '123456789',
                'nama' => 'john',
                'kelas' => null,
                'jurusan' => null,
                'saldo' => 0.00,
                'level' => 'admin',
                // password sesuai level: 'admin'
                'password' => Hash::make('456789'),
                'card_id' => '0003265966',
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'username' => '987654321',
                'nama' => 'doe',
                'kelas' => null,
                'jurusan' => null,
                'saldo' => 0.00,
                'level' => 'kantin',
                // password sesuai level: 'kantin'
                'password' => Hash::make('654321'),
                'card_id' => '4369872',
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'username' => '678954321',
                'nama' => 'pirja',
                'kelas' => 'XIIC',
                'jurusan' => 'RPL',
                'saldo' => 25000.00,
                'level' => 'siswa',
                // password sesuai level: 'siswa'
                'password' => Hash::make('954321'),
                'card_id' => '0004413093',
                'remember_token' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Hapus data lama (opsional). Jika tidak mau menghapus, komentar baris di bawah.
        // DB::table('users')->truncate();

        DB::table('users')->insert($users);
    }
}
