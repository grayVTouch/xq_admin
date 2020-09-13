<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        $modules = DB::table('xq_module')->get();
        $i = 0;
        $datetime = date('Y-m-d H:i:s');
        foreach ($modules as $v)
        {
            $index_id           = ++$i;
            $video_subject_id   = ++$i;
            $image_subject_id   = ++$i;
            $video_id           = ++$i;
            $image_id           = ++$i;
            $user_id            = ++$i;
            $channel_id         = ++$i;

            DB::table('xq_nav')->insert([
                [
                    'id'    => $index_id ,
                    'name'  => '首页' ,
                    'value' => 'index' ,
                    'description' => '' ,
                    'p_id'  => 0 ,
                    'is_menu' => 1 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,
                [
                    'id' => $video_subject_id ,
                    'name' => '视频专区' ,
                    'value' => 'video_subject' ,
                    'description' => '' ,
                    'p_id' => 0 ,
                    'is_menu' => 1 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,
                [
                    'id' => $image_subject_id ,
                    'name' => '图片专区' ,
                    'value' => 'image_subject' ,
                    'description' => '' ,
                    'p_id' => 0 ,
                    'is_menu' => 1 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,
                [
                    'id' => $video_id ,
                    'name' => '视频' ,
                    'value' => 'video' ,
                    'description' => '' ,
                    'p_id' => 0 ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,

                [
                    'id' => $image_id ,
                    'name' => '图片' ,
                    'value' => 'image' ,
                    'description' => '' ,
                    'p_id' => 0 ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,

                [
                    'id' => $user_id ,
                    'name' => '用户中心' ,
                    'value' => 'user' ,
                    'description' => '' ,
                    'p_id' => 0 ,
                    'is_menu' => 1 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,

                [
                    'id' => ++$i ,
                    'name' => '详情' ,
                    'description' => '图片专题详情' ,
                    'value' => 'detail_in_image_subject' ,
                    'p_id' => $image_subject_id ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,

                [
                    'id' => ++$i ,
                    'name' => '详情' ,
                    'value' => 'detail_in_video_subject' ,
                    'description' => '视频专题详情' ,
                    'p_id' => $video_subject_id ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,

                [
                    'id' => ++$i ,
                    'name' => '详情' ,
                    'value' => 'detail_in_video' ,
                    'description' => '视频详情' ,
                    'p_id' => $video_id ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,
//
                [
                    'id' => ++$i ,
                    'name' => '详情' ,
                    'value' => 'detail_in_image' ,
                    'description' => '图片详情' ,
                    'p_id' => $image_id ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,

                [
                    'id' => ++$i ,
                    'name' => '搜索' ,
                    'value' => 'search_in_video_subject' ,
                    'description' => '搜索-视频专题' ,
                    'p_id' => $video_subject_id ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,

                [
                    'id' => ++$i ,
                    'name' => '搜索' ,
                    'value' => 'search_in_image_subject' ,
                    'description' => '搜索-图片专题' ,
                    'p_id' => $image_subject_id ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,

                [
                    'id' => ++$i ,
                    'name' => '搜索' ,
                    'value' => 'search_in_image' ,
                    'description' => '搜索-图片' ,
                    'p_id' => $image_id ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,

                [
                    'id' => ++$i ,
                    'name' => '搜索' ,
                    'value' => 'search_in_video' ,
                    'description' => '搜索-视频' ,
                    'p_id' => $video_id ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,

                [
                    'id' => ++$i ,
                    'name' => '我的信息' ,
                    'value' => 'info_in_user' ,
                    'description' => '我的信息【用户中心】' ,
                    'p_id' => $user_id ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,

                [
                    'id' => ++$i ,
                    'name' => '修改密码' ,
                    'value' => 'password_in_user' ,
                    'description' => '修改密码【用户中心】' ,
                    'p_id' => $user_id ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,

                [
                    'id' => ++$i ,
                    'name' => '历史记录' ,
                    'value' => 'history_in_user' ,
                    'description' => '历史记录【用户中心】' ,
                    'p_id' => $user_id ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,

                [
                    'id' => ++$i ,
                    'name' => '历史记录' ,
                    'value' => 'favorites_in_user' ,
                    'description' => '我的收藏【用户中心】' ,
                    'p_id' => $user_id ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,

                [
                    'id' => $channel_id ,
                    'name' => '频道' ,
                    'value' => 'channel' ,
                    'description' => '' ,
                    'p_id' => 0 ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,

                [
                    'id' => ++$i ,
                    'name' => '图片' ,
                    'value' => 'image_in_channel' ,
                    'description' => '图片【频道】' ,
                    'p_id' => $channel_id ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,


                [
                    'id' => ++$i ,
                    'name' => '我关注的人' ,
                    'value' => 'my_focus_user_in_channel' ,
                    'description' => '我关注的人【频道】' ,
                    'p_id' => $channel_id ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,

                [
                    'id' => ++$i ,
                    'name' => '关注我的人' ,
                    'value' => 'focus_me_user_in_channel' ,
                    'description' => '关注我的人【频道】' ,
                    'p_id' => $channel_id ,
                    'is_menu' => 0 ,
                    'enable' => 1 ,
                    'weight' => 0 ,
                    'module_id' => $v->id ,
                    'platform' => 'web' ,
                    'is_built_in' => 1 ,
                ] ,
            ]);
        }
    }
}
