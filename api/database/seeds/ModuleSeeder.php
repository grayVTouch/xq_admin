<?php

use App\Model\ModuleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $datetime = date('Y-m-d H:i:s');
        ModuleModel::insert([
            [
                'id' => 1 ,
                'name' => '开发模式' ,
                'is_enabled' => 1 ,
                'auth' => 1 ,
                'description' => '' ,
                'weight' => 0 ,
                'default' => 0 ,
                'created_at' => $datetime ,
            ] ,
            [
                'id' => 2 ,
                'name' => '用户模式' ,
                'is_enabled' => 1 ,
                'auth' => 0 ,
                'description' => '描述' ,
                'weight' => 0 ,
                'default' => 1 ,
                'created_at' => $datetime ,
            ] ,
        ]);
    }
}
