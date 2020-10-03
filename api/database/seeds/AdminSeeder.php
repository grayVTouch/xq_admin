<?php

use App\Model\AdminModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datetime = date('Y-m-d H:i:s');
        AdminModel::insert([
            'username' => config('my.admin_username') ,
            'password' => Hash::make(config('my.admin_password')) ,
            'is_root' => 1 ,
            'role_id' => 1 ,
            'updated_at' =>  $datetime ,
            'created_at' => $datetime ,
        ]);
    }
}
