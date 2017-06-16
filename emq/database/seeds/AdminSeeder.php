<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('admins')->delete();
        DB::table('admins')->insert([
        	'user_id' => 1,
        	'role' => 3
        ]);

    }
}
