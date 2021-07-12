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
        DB::table('xq_user')->updateOrInsert([
            'username'      => config('my.client_username') ,
        ] , [
            'password'      => Hash::make(config('my.client_password')) ,
            'is_system'     => 1 ,
            'created_at'    => $datetime ,
        ]);
    }
}
