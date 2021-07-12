<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebRouteMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $datetime = date('Y-m-d H:i:s');
        $data = [
            [
                'name' => '图片专题-详情 url' ,
                'symbol' => 'ImageProject::show' ,
                'created_at' => $datetime ,
            ] ,
            [
                'name' => '视频专题-详情 url' ,
                'symbol' => 'VideoProject::show' ,
                'created_at' => $datetime ,
            ] ,
            [
                'name' => '视频-详情 url' ,
                'symbol' => 'Video::show' ,
                'created_at' => $datetime ,
            ] ,
            [
                'name' => '图片杂项-详情 url' ,
                'symbol' => 'Image::show' ,
                'created_at' => $datetime ,
            ] ,
        ];
        try {
            DB::beginTransaction();
            foreach ($data as $v)
            {
                $where = [
                    'symbol' => $v['symbol'] ,
                ];
                $insertData = $v;
                unset($insertData['symbol']);
                DB::table('xq_web_route_mapping')->updateOrInsert($where , $insertData);
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
