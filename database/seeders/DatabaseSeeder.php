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
        // User::factory(10)->create();
        // DB::table('users')->insert(
        //     [
        //         [
        //             'name' => 'Timothy Arella',
        //             'email' => 'timothyarella7@gmail.com',
        //             'phone' => '089626124115',
        //             'password' => '$2y$12$17Fy1AkLcPTCq8qKtn8TdOPgecTSTI9c26wHe57YqQuPxtOWA4yU2',
        //             'roles_id' => 1,
        //             'email_verified_at' => now(),
        //             'created_at' => now()
        //         ],
        //         [
        //             'name' => 'User2',
        //             'email' => 'user2@mail.com',
        //             'phone' => '089626124116',
        //             'password' => '$2y$12$17Fy1AkLcPTCq8qKtn8TdOPgecTSTI9c26wHe57YqQuPxtOWA4yU2',
        //             'roles_id' => 2,
        //             'email_verified_at' => now(),
        //             'created_at' => now(),
        //         ],
        //         [
        //             'name' => 'User3',
        //             'email' => 'user3@mail.com',
        //             'phone' => '089626124117',
        //             'password' => '$2y$12$17Fy1AkLcPTCq8qKtn8TdOPgecTSTI9c26wHe57YqQuPxtOWA4yU2',
        //             'roles_id' => 3,
        //             'email_verified_at' => now(),
        //             'created_at' => now(),
        //         ],
        //         [
        //             'name' => 'User4',
        //             'email' => 'user4@mail.com',
        //             'phone' => '089626124118',
        //             'password' => '$2y$12$17Fy1AkLcPTCq8qKtn8TdOPgecTSTI9c26wHe57YqQuPxtOWA4yU2',
        //             'roles_id' => 4,
        //             'email_verified_at' => now(),
        //             'created_at' => now(),
        //         ],
        //         [
        //             'name' => 'User5',
        //             'email' => 'user5@mail.com',
        //             'phone' => '089626124119',
        //             'password' => '$2y$12$17Fy1AkLcPTCq8qKtn8TdOPgecTSTI9c26wHe57YqQuPxtOWA4yU2',
        //             'roles_id' => 4,
        //             'email_verified_at' => now(),
        //             'created_at' => now(),
        //         ],
        //     ]
        // );

        DB::table('roles')->insert(
            [
                ['name' => 'Chief Executive Officer'],
                ['name' => 'Financial Officer'],
                ['name' => 'Operational Officer'],
                ['name' => 'Staff']
            ]
        );
    }
}
