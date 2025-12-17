<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('barangs')->insert([
            [
                'nama_barang' => 'fantaa',
                'harga' => 8000,
                'kode' => '764460145090',
                'stok' => 50,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_barang' => 'KIT Motor Paste Wax',
                'harga' => 5000,
                'kode' => '8992779261909',
                'stok' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_barang' => 'penggaris',
                'harga' => 3000,
                'kode' => '8901180635049',
                'stok' => 100,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_barang' => 'sambal',
                'harga' => 2000,
                'kode' => '8997021870908',
                'stok' => 75,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_barang' => 'korek',
                'harga' => 2000,
                'kode' => '1122334455',
                'stok' => 60,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_barang' => 'saos tomat',
                'harga' => 8000,
                'kode' => '8998888710147',
                'stok' => 40,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_barang' => 'buku',
                'harga' => 2000,
                'kode' => '8991389221006',
                'stok' => 120,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_barang' => 'hp',
                'harga' => 5000000,
                'kode' => '866829063302868',
                'stok' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_barang' => 'Marina UV White',
                'harga' => 10000,
                'kode' => '8999908450906',
                'stok' => 35,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_barang' => 'tipe x',
                'harga' => 5000,
                'kode' => '8993988055174',
                'stok' => 45,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_barang' => 'likudi',
                'harga' => 100000,
                'kode' => '8997241600767',
                'stok' => 10,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
