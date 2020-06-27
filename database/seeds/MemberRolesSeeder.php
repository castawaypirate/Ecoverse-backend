<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MemberRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('member_roles')->insert([
            [
                'name' => 'admin',
                'create' => '1',
                'edit' => '1',
                'delete' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'guest',
                'create' => '1',
                'edit' => '0',
                'delete' => '0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
