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
                'updated_at' =>  $datetime ,
                'created_at' => $datetime ,
            ] ,
            [
                'name' => '图片专题' ,
                'value' => 'image_subject' ,
                'platform' => 'web' ,
                'updated_at' =>  $datetime ,
                'created_at' => $datetime ,
            ] ,
            [
                'name' => '视频专题' ,
                'value' => 'video_subject' ,
                'platform' => 'web' ,
                'updated_at' =>  $datetime ,
                'created_at' => $datetime ,
            ] ,
        ]);
    }
}
