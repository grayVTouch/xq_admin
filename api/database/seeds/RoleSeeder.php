<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('xq_role')->insert([
            'id' => 1 ,
            'name' => '超级管理员' ,
            'weight' => 0 ,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
