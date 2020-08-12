<?php

use App\Model\NavModel;
use Illuminate\Database\Seeder;

class NavSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $module_ids = [1 , 2];
        $i = 0;
        foreach ($module_ids as $v)
        {
            NavModel::insert([
                [
                    'id' => ++$i ,
                    'name' => '首页' ,
                    'value' => '/index' ,
                    'p_id' => 0 ,
                    'is_menu' => 1 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v ,
                    'platform' => 'web' ,
                ] ,
                [
                    'id' => ++$i ,
                    'name' => '视频专区' ,
                    'value' => '/video' ,
                    'p_id' => 0 ,
                    'is_menu' => 1 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v ,
                    'platform' => 'web' ,
                ] ,
                [
                    'id' => ++$i ,
                    'name' => '图片专区' ,
                    'value' => '/image' ,
                    'p_id' => 0 ,
                    'is_menu' => 1 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v ,
                    'platform' => 'web' ,
                ] ,
                [
                    'id' => ++$i ,
                    'name' => '用户中心' ,
                    'value' => '/user' ,
                    'p_id' => 0 ,
                    'is_menu' => 1 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v ,
                    'platform' => 'web' ,
                ] ,
            ]);
        }
    }
}
