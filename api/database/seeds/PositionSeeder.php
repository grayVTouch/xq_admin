<?php

use App\Model\PositionModel;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datetime = date('Y-m-d H:i:s');
        PositionModel::insert([
            [
                'name' => '首页幻灯片' ,
                'value' => 'home_slideshow' ,
                'platform' => 'web' ,
                'description' => '' ,
                'updated_at' =>  $datetime ,
                'created_at' => $datetime ,
            ] ,
            [
                'name' => '图片专题' ,
                'value' => 'image_project' ,
                'platform' => 'web' ,
                'description' => '' ,
                'updated_at' =>  $datetime ,
                'created_at' => $datetime ,
            ] ,
            [
                'name' => '视频专题' ,
                'value' => 'video_project' ,
                'platform' => 'web' ,
                'description' => '' ,
                'updated_at' =>  $datetime ,
                'created_at' => $datetime ,
            ] ,
            [
                'name' => '图片' ,
                'value' => 'image' ,
                'platform' => 'web' ,
                'description' => '非专题图片' ,
                'updated_at' =>  $datetime ,
                'created_at' => $datetime ,
            ] ,
            [
                'name' => '视频' ,
                'value' => 'video' ,
                'platform' => 'web' ,
                'description' => '非专题视频' ,
                'updated_at' =>  $datetime ,
                'created_at' => $datetime ,
            ] ,
        ]);
    }
}
