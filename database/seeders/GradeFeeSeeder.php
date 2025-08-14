<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeFeeSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['A','B','C','D','E','F'] as $g) {
            DB::table('grade')->updateOrInsert(['name' => $g], []);
        }

        $fees = [
            ['name' => 'Tuition', 'amount' => 5000.00],
            ['name' => 'Registration', 'amount' => 200.00],
            ['name' => 'Library', 'amount' => 100.00],
        ];

        foreach ($fees as $fee) {
            DB::table('fee')->updateOrInsert(['name' => $fee['name']], ['amount' => $fee['amount']]);
        }
    }
}


