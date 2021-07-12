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
        $datetime = date('Y-m-d H:i:s');

        DB::table('xq_role')->updateOrInsert([
            'name' => '超级管理员' ,
        ] , [
            'id' => 1 ,
            'weight' => 0 ,
            'created_at' =>  $datetime ,
        ]);
    }
}
