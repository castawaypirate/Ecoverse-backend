<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);

        // DB::table('users')->insert([
        //     'username' => Str::random(10),
        //     'role' => Str::random(10),
        //     'password' => Hash::make('password'),
        // ]);

        factory(App\User::class, 50)->create();
    }
}
