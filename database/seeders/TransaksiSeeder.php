<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 50; $i++) {

            $userId = collect([1, 2, 3])->random();

            $createdAt = Carbon::now()
                ->startOfMonth()
                ->addDays(rand(0, now()->daysInMonth - 1))
                ->addMinutes(rand(0, 1439));

            DB::table('transaksis')->insert([
                'user_id'    => $userId,
                'total'      => 0, // diupdate oleh DetailSeeder
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
