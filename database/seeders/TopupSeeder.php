<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topup;
use Carbon\Carbon;

class TopupSeeder extends Seeder
{
    public function run(): void
    {
        // pastikan user dengan id ini ADA
        $users = [1, 2, 3];

        foreach (range(1, 40) as $i) {
            Topup::create([
                'user_id' => $users[array_rand($users)],
                'total' => rand(10000, 200000), // nominal topup
                'created_at' => Carbon::now()
                    ->subDays(rand(0, 30))
                    ->subMinutes(rand(0, 1440)),
                'updated_at' => now(),
            ]);
        }
    }
}
