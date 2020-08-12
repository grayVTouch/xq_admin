<?php

use App\Model\RoleModel;
use Illuminate\Database\Seeder;

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
        RoleModel::insert([
            'id' => 1 ,
            'name' => '超级管理员' ,
            'weight' => 0 ,
            'create_time' => date('Y-m-d H:i:s')
        ]);
    }
}
