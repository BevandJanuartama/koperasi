<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $transaksis = DB::table('transaksis')->get();

        foreach ($transaksis as $transaksi) {

            $total = 0;
            $jumlahDetail = rand(1, 4);

            for ($i = 1; $i <= $jumlahDetail; $i++) {

                $qty = rand(1, 5);
                $harga = rand(2000, 15000);
                $subtotal = $qty * $harga;

                DB::table('detail_transaksis')->insert([
                    'transaksi_id' => $transaksi->id,
                    'barang_id'    => rand(1, 10),
                    'qty'          => $qty,
                    'harga_satuan' => $harga,
                    'subtotal'     => $subtotal,
                    'created_at'   => $transaksi->created_at,
                    'updated_at'   => $transaksi->created_at,
                ]);

                $total += $subtotal;
            }

            // update total di tabel transaksis
            DB::table('transaksis')
                ->where('id', $transaksi->id)
                ->update(['total' => $total]);
        }
    }
}
