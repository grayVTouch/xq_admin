<?php


namespace App\Customize\api\web\action;

use App\Customize\api\web\handler\CollectionGroupHandler;
use App\Customize\api\web\handler\ImageSubjectHandler;
use App\Customize\api\web\handler\RelationTagHandler;
use App\Customize\api\web\handler\SubjectHandler;
use App\Customize\api\web\model\CategoryModel;
use App\Customize\api\web\model\CollectionGroupModel;
use App\Customize\api\web\model\CollectionModel;
use App\Customize\api\web\model\HistoryModel;
use App\Customize\api\web\model\ImageSubjectModel;
use App\Customize\api\web\model\ModuleModel;
use App\Customize\api\web\model\PraiseModel;
use App\Customize\api\web\model\RelationTagModel;
use App\Customize\api\web\model\SubjectModel;
use App\Customize\api\web\model\TagModel;
use App\Http\Controllers\api\web\Base;
use Core\Lib\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\web\get_form_error;
use function api\web\my_config;
use function api\web\parse_order;
use function api\web\user;
use function core\current_time;
use function core\obj_to_array;

class ImageSubjectAction extends Action
{
    // 最新发布图片
    public static function newest(Base $context , array $param = [])
    {
        $type_range = array_keys(my_config('business.type_for_image_subject'));

        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'type'      => ['required' , Rule::in($type_range)] ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = ImageSubjectModel::getNewestByFilterAndLimit($param , $limit);
        $res = ImageSubjectHandler::handleAll($res);
        return self::success('' , $res);
    }

    // 热门
    public static function hot(Base $context , array $param = [])
    {
        $type_range = array_keys(my_config('business.type_for_image_subject'));

        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'type'      => ['required' , Rule::in($type_range)] ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = ImageSubjectModel::getHotByFilterAndLimit($param , $limit);
        $res = ImageSubjectHandler::handleAll($res);
        return self::success('' , $res);
    }

    public static function hotWithPager(Base $context , array $param = [])
    {
        $type_range = array_keys(my_config('business.type_for_image_subject'));

        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'type'      => ['required' , Rule::in($type_range)] ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = ImageSubjectModel::getHotWithPagerByFilterAndLimit($param , $limit);
        $res = ImageSubjectHandler::handlePaginator($res);
        return self::success('' , $res);
    }

    public static function getByTagId(Base $context , $tag_id , array $param = [])
    {
        $type_range = array_keys(my_config('business.type_for_image_subject'));

        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'type'      => ['required' , Rule::in($type_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $tag = TagModel::find($tag_id);
        if (empty($tag)) {
            return self::error('标签不存在');
        }
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = ImageSubjectModel::getByTagIdAndFilterAndLimit($tag->id , $param , $limit);
        $res = ImageSubjectHandler::handleAll($res);
        return self::success('' , $res);
    }

    public static function getWithPagerByTagIds(Base $context , array $param = [])
    {
        $type_range = array_keys(my_config('business.type_for_image_subject'));
        $mode_range = my_config('business.mode_for_image_subject');

        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'tag_ids'   => 'required' ,
            'mode'      => ['required' , Rule::in($mode_range)] ,
            'type'      => ['required' , Rule::in($type_range)] ,
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
                $res = ImageSubjectModel::getInStrictByTagIdsAndFilterAndLimit($tag_ids , $param , $limit);
                break;
            case 'loose':
                $res = ImageSubjectModel::getByTagIdsAndFilterAndLimit($tag_ids , $param , $limit);
                break;
            default:
                return self::error('不支持的 mode ，当前受支持的 mode 有：' . implode(' , ' , $mode_range));
        }

        $res = ImageSubjectHandler::handlePaginator($res);
        return self::success('' , $res);
    }


    public static function hotTags(Base $context , array $param = [])
    {
        $type_range = array_keys(my_config('business.type_for_image_subject'));

        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'type'      => ['required' , Rule::in($type_range)] ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , '' , 404);
        }

        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = RelationTagModel::hotTagsInImageSubjectByFilterAndLimit($param , $limit);
        $res = RelationTagHandler::handleAll($res);
        return self::success('' , $res);
    }

    public static function hotTagsWithPager(Base $context , array $param = [])
    {
        $type_range = array_keys(my_config('business.type_for_image_subject'));

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
        $res = ImageSubjectModel::getNewestWithPagerByFilterAndLimit($param , $limit);
        $res = ImageSubjectHandler::handlePaginator($res);
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
        $image_subject = ImageSubjectModel::find($id);
        if (empty($image_subject)) {
            return self::error('图片专题不存在' , '' , 404);
        }
        $image_subject = ImageSubjectHandler::handle($image_subject);
        return self::success('' , $image_subject);
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
        $categories = CategoryModel::getByModuleIdAndType($module->id , 'image_subject');
        $categories = obj_to_array($categories);
        $categories = Category::childrens(0 , $categories , null , false , false);
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
        $type_range = array_keys(my_config('business.type_for_image_subject'));
        $mode_range = my_config('business.mode_for_image_subject');

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
                $res = ImageSubjectModel::getWithPagerInStrictByFilterAndOrderAndLimit($param , $order , $limit);
                break;
            case 'loose':
                $res = ImageSubjectModel::getWithPagerInLooseByFilterAndOrderAndLimit($param , $order , $limit);
                break;
            default:
                return self::error('不支持的搜索模式，当前支持的模式有：' . implode(' , ' , $mode_range));
        }
        $res = ImageSubjectHandler::handlePaginator($res);
        return self::success('' , $res);
    }

    public static function incrementViewCount(Base $context , int $image_subject_id , array $param = [])
    {
        $image_subject = ImageSubjectModel::find($image_subject_id);
        if (empty($image_subject)) {
            return self::error('图片专题不存在');
        }
        ImageSubjectModel::countHandle($image_subject->id , 'view_count' , 'increment' , 1);
        return self::success();
    }

    // 推荐数据
    public static function recommend(Base $context , int $image_subject_id , array $param = [])
    {
        $type_range = array_keys(my_config('business.type_for_image_subject'));

        $validator = Validator::make($param , [
            'type'      => ['required' , Rule::in($type_range)] ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }

        $image_subject = ImageSubjectModel::find($image_subject_id);
        if (empty($image_subject)) {
            return self::error('图片专题未找到' , null , 404);
        }

        $param['module_id']     = $image_subject->module_id ?? '';
        $param['category_id']   = $image_subject->category_id ?? '';
        $param['subject_id']    = $image_subject->subject_id ?? '';

        $limit = $param['limit'] ? $param['limit'] : my_config('app.limit');

        $res = ImageSubjectModel::recommendExcludeSelfByFilterAndLimit($image_subject->id , $param , $limit);
        $res = ImageSubjectHandler::handleAll($res);
        return self::success('' , $res);
    }
}
