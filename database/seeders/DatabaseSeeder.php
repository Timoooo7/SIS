<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        setlocale(LC_TIME, 'IND');

        // Seeds roles table
        DB::table('roles')->insert(
            [
                ['name' => 'Chief Executive Officer'],
                ['name' => 'Financial Officer'],
                ['name' => 'Operational Officer'],
                ['name' => 'Staff']
            ]
        );

        // Seeds balance table
        DB::table('blaterian_food_balance')->insert(
            [
                [
                    'expense' => 0,
                    'income' => 0,
                    'balance' => 0
                ],
            ]
        );
    }
}
