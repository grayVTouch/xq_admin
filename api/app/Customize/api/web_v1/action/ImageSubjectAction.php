<?php


namespace App\Customize\api\web_v1\action;

use App\Customize\api\web_v1\handler\ImageSubjectHandler;
use App\Customize\api\web_v1\handler\RelationTagHandler;
use App\Customize\api\web_v1\model\ImageSubjectModel;
use App\Customize\api\web_v1\model\ModuleModel;
use App\Customize\api\web_v1\model\RelationTagModel;
use App\Customize\api\web_v1\model\TagModel;
use App\Http\Controllers\api\web_v1\Base;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\web_v1\my_config;

class ImageSubjectAction extends Action
{
    // 最新发布图片
    public static function newest(Base $context , array $param = [])
    {
        if (empty($param['module_id'])) {
            return self::error('请提供 module_id');
        }
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = ImageSubjectModel::getNewestByModuleIdAndLimit($param['module_id'] , $limit);
        $res = ImageSubjectHandler::handleAll($res);
        return self::success($res);
    }

    // 热门
    public static function hot(Base $context , array $param = [])
    {
        if (empty($param['module_id'])) {
            return self::error('请提供 module_id');
        }
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = ImageSubjectModel::getHotByModuleIdAndLimit($param['module_id'] , $limit);
        $res = ImageSubjectHandler::handleAll($res);
        return self::success($res);
    }

    public static function hotWithPager(Base $context , array $param = [])
    {
        if (empty($param['module_id'])) {
            return self::error('请提供 module_id');
        }
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = ImageSubjectModel::getHotWithPagerByModuleIdAndLimit($param['module_id'] , $limit);
        $res = ImageSubjectHandler::handlePaginator($res);
        return self::success($res);
    }

    public static function getByTagId(Base $context , $tag_id , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id'             => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $tag = TagModel::find($tag_id);
        if (empty($tag)) {
            return self::error('标签不存在');
        }
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = ImageSubjectModel::getByModuleIdAndTagIdAndLimit($param['module_id'] , $tag->id , $limit);
        $res = ImageSubjectHandler::handleAll($res);
        return self::success($res);
    }

    public static function getWithPagerByTagIds(Base $context , array $param = [])
    {
        $mode_range = my_config('business.mode_for_image_subject');
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'tag_ids' => 'required' ,
            'mode' => ['required' , Rule::in($mode_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , 404);
        }
        $tag_ids = empty($param['tag_ids']) ? [] : json_decode($param['tag_ids'] , true);
        if (empty($tag_ids)) {
            return self::error('请提供过滤的标签');
        }
        $tags = TagModel::getByIds($tag_ids);
        if (count($tags) !== count($tag_ids)) {
            return self::error('部分或全部标签未找到');
        }
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        switch ($param['mode'])
        {
            case 'strict':
                $res = ImageSubjectModel::getInStrictByModuleIdAndTagIdsAndLimit($param['module_id'] , $tag_ids , $limit);
                break;
            case 'loose':
                $res = ImageSubjectModel::getByModuleIdAndTagIdsAndLimit($param['module_id'] , $tag_ids , $limit);
                break;
            default:
                return self::error('不支持的 mode ，当前受支持的 mode 有：' . implode(' , ' , $mode_range));
        }
        $res = ImageSubjectHandler::handlePaginator($res);
        return self::success($res);
    }


    public static function hotTags(Base $context , array $param = [])
    {
        if (empty($param['module_id'])) {
            return self::error('请提供 module_id');
        }
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = RelationTagModel::hotTagsByModuleIdAndRelationTableAndLimit($param['module_id'] , 'xq_image_subject' , $limit);
        $res = RelationTagHandler::handleAll($res);
        return self::success($res);
    }

    public static function hotTagsWithPager(Base $context , array $param = [])
    {
        if (empty($param['module_id'])) {
            return self::error('请提供 module_id');
        }
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = RelationTagModel::hotTagsWithPagerByModuleIdAndRelationTableAndLimit($param['module_id'] , 'xq_image_subject' , $limit);
        $res = RelationTagHandler::handlePaginator($res);
        return self::success($res);
    }

    public static function newestWithPager(Base $context , array $param = [])
    {
        if (empty($param['module_id'])) {
            return self::error('请提供 module_id');
        }
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = ImageSubjectModel::getNewestWithPagerByModuleIdAndLimit($param['module_id'] , $limit);
        $res = ImageSubjectHandler::handlePaginator($res);
        return self::success($res);
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , 404);
        }
        $image_subject = ImageSubjectModel::find($id);
        $image_subject = ImageSubjectHandler::handle($image_subject);
        return self::success($image_subject);
    }
}
