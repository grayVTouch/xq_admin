<?php


namespace App\Customize\api\web_v1\action;

use App\Customize\api\web_v1\handler\CollectionGroupHandler;
use App\Customize\api\web_v1\handler\VideoSubjectHandler;
use App\Customize\api\web_v1\handler\RelationTagHandler;
use App\Customize\api\web_v1\handler\SubjectHandler;
use App\Customize\api\web_v1\model\CategoryModel;
use App\Customize\api\web_v1\model\CollectionGroupModel;
use App\Customize\api\web_v1\model\CollectionModel;
use App\Customize\api\web_v1\model\HistoryModel;
use App\Customize\api\web_v1\model\VideoSubjectModel;
use App\Customize\api\web_v1\model\ModuleModel;
use App\Customize\api\web_v1\model\PraiseModel;
use App\Customize\api\web_v1\model\RelationTagModel;
use App\Customize\api\web_v1\model\SubjectModel;
use App\Customize\api\web_v1\model\TagModel;
use App\Http\Controllers\api\web_v1\Base;
use Core\Lib\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\web_v1\get_form_error;
use function api\web_v1\my_config;
use function api\web_v1\parse_order;
use function api\web_v1\user;
use function core\current_time;
use function core\obj_to_array;

class VideoSubjectAction extends Action
{
    public static function newest(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = VideoSubjectModel::getNewestByFilterAndLimit($param , $limit);
        $res = VideoSubjectHandler::handleAll($res);
        return self::success('' , $res);
    }

    public static function hot(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = VideoSubjectModel::getHotByFilterAndLimit($param , $limit);
        $res = VideoSubjectHandler::handleAll($res);
        return self::success('' , $res);
    }

    public static function hotWithPager(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = VideoSubjectModel::getHotWithPagerByFilterAndLimit($param , $limit);
        $res = VideoSubjectHandler::handlePaginator($res);
        return self::success('' , $res);
    }

    public static function getByTagId(Base $context , $tag_id , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $tag = TagModel::find($tag_id);
        if (empty($tag)) {
            return self::error('标签不存在');
        }

        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = VideoSubjectModel::getByTagIdAndFilterAndLimit($tag->id , $param , $limit);
        $res = VideoSubjectHandler::handleAll($res);
        return self::success('' , $res);
    }

    public static function getWithPagerByTagIds(Base $context , array $param = [])
    {
        $mode_range = my_config('business.mode_for_video_subject');

        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'tag_ids'   => 'required' ,
            'mode'      => ['required' , Rule::in($mode_range)] ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , '' , 404);
        }

        $tag_ids = empty($param['tag_ids']) ? [] : json_decode($param['tag_ids'] , true);
        if (empty($tag_ids)) {
            return self::error('请提供过滤的标签');
        }

        $tags = TagModel::find($tag_ids);
        if (count($tags) !== count($tag_ids)) {
            return self::error('部分或全部标签未找到');
        }

        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];

        switch ($param['mode'])
        {
            case 'strict':
                $res = VideoSubjectModel::getInStrictByTagIdsAndFilterAndLimit($tag_ids , $param , $limit);
                break;
            case 'loose':
                $res = VideoSubjectModel::getByTagIdsAndFilterAndLimit($tag_ids , $param , $limit);
                break;
            default:
                return self::error('不支持的 mode ，当前受支持的 mode 有：' . implode(' , ' , $mode_range));
        }

        $res = VideoSubjectHandler::handlePaginator($res);
        return self::success('' , $res);
    }


    public static function hotTags(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , '' , 404);
        }

        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = RelationTagModel::hotTagsInVideoSubjectByFilterAndLimit($param , $limit);
        $res = RelationTagHandler::handleAll($res);
        return self::success('' , $res);
    }

    public static function hotTagsWithPager(Base $context , array $param = [])
    {
        $type_range = array_keys(my_config('business.type_for_video_subject'));

        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'type'      => ['required' , Rule::in($type_range)] ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }

        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }

        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = RelationTagModel::hotTagsWithPagerInImageSubjectByValueAndFilterAndLimit($param['value'] , $param , $limit);
        $res = RelationTagHandler::handlePaginator($res);
        return self::success('' , $res);
    }

    public static function newestWithPager(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , '' , 404);
        }

        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = VideoSubjectModel::getNewestWithPagerByFilterAndLimit($param , $limit);
        $res = VideoSubjectHandler::handlePaginator($res);
        return self::success('' , $res);
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
            return self::error('模块不存在' , '' , 404);
        }
        $video_subject = VideoSubjectModel::find($id);
        if (empty($video_subject)) {
            return self::error('图片专题不存在' , '' , 404);
        }
        $video_subject = VideoSubjectHandler::handle($video_subject);
        return self::success('' , $video_subject);
    }

    public static function category(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , '' , 404);
        }
        $all_categories = CategoryModel::getAllByModuleId($module->id);
        // 图片分类 id ，请比对数据库中的数据，指定具体的 id
        $video_subject_top_category_id = 0;
        switch ($module->id)
        {
            // 新世界
            case 1:
                $video_subject_top_category_id = 16;
                break;
            // 旧世界
            case 3:
                $video_subject_top_category_id = 36;
                break;
            default:
                return self::error('不支持的模块，请重新选择');
        }
        $all_categories = obj_to_array($all_categories);
        $categories = Category::childrens($video_subject_top_category_id , $all_categories , null , true , false);
        return self::success('' , $categories);
    }

    public static function subject(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , '' , 404);
        }
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = SubjectModel::getWithPagerInImageSubjectByModuleIdAndValueAndLimit($module->id , $param['value'] , $limit);
        $res = SubjectHandler::handlePaginator($res);
        return self::success('' , $res);
    }

    public static function index(Base $context , array $param = [])
    {
        $type_range = array_keys(my_config('business.type_for_video_subject'));
        $mode_range = my_config('business.mode_for_video_subject');

        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'mode'      => ['required' , Rule::in($mode_range)] ,
            'type'      => ['required' , Rule::in($type_range)] ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }

        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , '' , 404);
        }

        $param['category_ids']   = $param['category_ids'] === '' ? [] : json_decode($param['category_ids'] , true);
        $param['subject_ids']    = $param['subject_ids'] === '' ? [] : json_decode($param['subject_ids'] , true);
        $param['tag_ids']        = $param['tag_ids'] === '' ? [] : json_decode($param['tag_ids'] , true);

        $order                   = $param['order'] === '' ? null : parse_order($param['order']);
        $limit                   = empty($param['limit']) ? my_config('app.limit') : $param['limit'];

        // 获取所有子类
        $categories         = CategoryModel::getAll();
        $categories         = obj_to_array($categories);
        $tmp_category_ids   = [];

        foreach ($param['category_ids'] as $v)
        {
            $childrens          = Category::childrens($v , $categories , null , true , false);
            $ids                = array_column($childrens , 'id');
            $tmp_category_ids   = array_merge($tmp_category_ids , $ids);
        }

        $param['category_ids'] = array_unique($tmp_category_ids);
        $res = [];
        switch ($param['mode'])
        {
            case 'strict':
                $res = VideoSubjectModel::getWithPagerInStrictByFilterAndOrderAndLimit($param , $order , $limit);
                break;
            case 'loose':
                $res = VideoSubjectModel::getWithPagerInLooseByFilterAndOrderAndLimit($param , $order , $limit);
                break;
            default:
                return self::error('不支持的搜索模式，当前支持的模式有：' . implode(' , ' , $mode_range));
        }
        $res = VideoSubjectHandler::handlePaginator($res);
        return self::success('' , $res);
    }

    public static function incrementViewCount(Base $context , int $video_subject_id , array $param = [])
    {
        $video_subject = VideoSubjectModel::find($video_subject_id);
        if (empty($video_subject)) {
            return self::error('图片专题不存在');
        }
        VideoSubjectModel::countHandle($video_subject->id , 'view_count' , 'increment' , 1);
        return self::success();
    }

    // 推荐数据
    public static function recommend(Base $context , int $video_subject_id , array $param = [])
    {
        $type_range = array_keys(my_config('business.type_for_video_subject'));

        $validator = Validator::make($param , [
            'type'      => ['required' , Rule::in($type_range)] ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }

        $video_subject = VideoSubjectModel::find($video_subject_id);
        if (empty($video_subject)) {
            return self::error('图片专题未找到' , null , 404);
        }

        $param['module_id']     = $video_subject->module_id ?? '';
        $param['category_id']   = $video_subject->category_id ?? '';
        $param['subject_id']    = $video_subject->subject_id ?? '';

        $limit = $param['limit'] ? $param['limit'] : my_config('app.limit');

        $res = VideoSubjectModel::recommendExcludeSelfByFilterAndLimit($video_subject->id , $param , $limit);
        $res = VideoSubjectHandler::handleAll($res);
        return self::success('' , $res);
    }
}
