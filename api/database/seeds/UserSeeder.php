<?php

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
        $datetime = date('Y-m-d H:i:s');
        DB::table('xq_user')->insert([
            'username'      => 'admin' ,
            'password'      => Hash::make('123456') ,
            'is_system'     => 1 ,
            'updated_at'    => $datetime ,
            'created_at'    => $datetime ,
        ]);
    }
}
