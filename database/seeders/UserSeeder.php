<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserData;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(1)
        ->state([
            'id' => 1,
            'name' => env('MASTER_NAME'),
            'email' => env('MASTER_EMAIL'),
            'email_verified_at' => now(),
            'password' => Hash::make(env('MASTER_PASSWORD')),
            'remember_token' => null,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ])
        ->has(UserData::factory())
        ->create();
        

        // Access Token for Testing Purposes
        DB::table('personal_access_tokens')->insert([
            'id' => 1,
            'tokenable_type' => 'App\Models\User',
            'tokenable_id' => 1,
            'name' => 'testing-token',
            'token' => '24c48f04a75fc5a90bacc1274f8fef58a25e609781a1f52bbc5189fb04c079e9', //1|8nY8LAasXPVYMbP1OU6jh6xIf6mJZP7srRmlq2TS
            'abilities' => '[\"*\"]',
            'last_used_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
