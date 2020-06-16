<?php


namespace App\Customize\api\admin_v1\action;

use App\Customize\api\admin_v1\handler\ImageSubjectHandler;
use App\Customize\api\admin_v1\model\CategoryModel;
use App\Customize\api\admin_v1\model\ImageModel;
use App\Customize\api\admin_v1\model\ImageSubjectModel;
use App\Customize\api\admin_v1\model\ModuleModel;
use App\Customize\api\admin_v1\model\SubjectModel;
use App\Customize\api\admin_v1\model\UserModel;
use App\Http\Controllers\api\admin_v1\Base;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin_v1\get_form_error;
use function api\admin_v1\my_config;
use function api\admin_v1\parse_order;
use function core\array_unit;

class ImageSubjectAction extends Action
{
    public static function index(Base $context , array $param = []): array
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $limit = $param['limit'] === '' ? my_config('app.limit') : $param['limit'];
        $paginator = ImageSubjectModel::index($param , $order , $limit);
        $paginator = ImageSubjectHandler::handlePaginator($paginator);
        return self::success($paginator);
    }

    public static function update(Base $context , $id , array $param = []): array
    {
        $type_range = array_keys(my_config('business.type_for_image_subject'));
        $status_range = array_keys(my_config('business.status_for_image_subject'));
        $validator = Validator::make($param , [
            'name'      => 'required' ,
            'user_id'   => 'required|integer' ,
            'module_id' => 'required|integer' ,
            'category_id' => 'required|integer' ,
            'subject_id' => 'sometimes|integer' ,
            'type'      => ['required' , Rule::in($type_range)] ,
            'weight'    => 'sometimes|integer' ,
            'view_count'    => 'sometimes|integer' ,
            'praise_count'  => 'sometimes|integer' ,
            'status'        => ['required' , 'integer' , Rule::in($status_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $image_subject = ImageSubjectModel::find($id);
        if (empty($image_subject)) {
            return self::error('图片专题不存在' , 404);
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error([
                'module_id' => '模块不存在'
            ]);
        }
        $category = CategoryModel::find($param['category_id']);
        if (empty($category)) {
            return self::error([
                'category_id' => '分类不存在' ,
            ]);
        }
        $user = UserModel::find($param['user_id']);
        if (empty($user)) {
            return self::error([
                'user_id' => '用户不存在'
            ]);
        }
        $subject = null;
        if ($param['type'] === 'pro') {
            $subject = SubjectModel::find($param['subject_id']);
            if (empty($subject)) {
                return self::error([
                    'subject_id' => '专题不存在' ,
                ]);
            }
        }
        if ($param['status'] !== '' && $param['status'] == -1 && $param['fail_reason'] === '') {
            return self::error([
                'fail_reason' => '请提供失败原因' ,
            ]);
        }
        $param['tag']    = $param['tag'] === '' ? '[]' : $param['tag'];
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        $param['create_time'] = $param['create_time'] === '' ? date('Y-m-d H:i:s') : $param['create_time'];
        $images = $param['images'] === '' ? [] : json_decode($param['images'] , true);
        try {
            DB::beginTransaction();
            ImageSubjectModel::updateById($image_subject->id , array_unit($param , [
                'name' ,
                'user_id' ,
                'module_id' ,
                'category_id' ,
                'type' ,
                'subject_id' ,
                'tag' ,
                'thumb' ,
                'description' ,
                'weight' ,
                'view_count' ,
                'praise_count' ,
                'status' ,
                'fail_reason' ,
                'create_time' ,
            ]));
            ImageModel::delByImageSubjectId($image_subject->id);
            foreach ($images as $v)
            {
                ImageModel::insertGetId([
                    'image_subject_id' => $image_subject->id ,
                    'path' => $v
                ]);
            }
            DB::commit();
            return self::success();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function store(Base $context , array $param = []): array
    {
        $type_range = array_keys(my_config('business.type_for_image_subject'));
        $status_range = array_keys(my_config('business.status_for_image_subject'));
        $validator = Validator::make($param , [
            'name'      => 'required' ,
            'user_id'   => 'required|integer' ,
            'module_id' => 'required|integer' ,
            'category_id' => 'required|integer' ,
            'subject_id' => 'sometimes|integer' ,
            'type'      => ['required' , Rule::in($type_range)] ,
            'weight'    => 'sometimes|integer' ,
            'view_count'    => 'sometimes|integer' ,
            'praise_count'  => 'sometimes|integer' ,
            'status'        => ['required' , 'integer' , Rule::in($status_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error([
                'module_id' => '模块不存在'
            ]);
        }
        $category = CategoryModel::find($param['category_id']);
        if (empty($category)) {
            return self::error([
                'category_id' => '分类不存在' ,
            ]);
        }
        $user = UserModel::find($param['user_id']);
        if (empty($user)) {
            return self::error([
                'user_id' => '用户不存在'
            ]);
        }
        $subject = null;
        if ($param['type'] === 'pro') {
            $subject = SubjectModel::find($param['subject_id']);
            if (empty($subject)) {
                return self::error([
                    'subject_id' => '专题不存在' ,
                ]);
            }
        }
        if ($param['status'] !== '' && $param['status'] == -1 && $param['fail_reason'] === '') {
            return self::error([
                'fail_reason' => '请提供失败原因' ,
            ]);
        }
        $param['tag']    = $param['tag'] === '' ? '[]' : $param['tag'];
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        $param['create_time'] = $param['create_time'] === '' ? date('Y-m-d H:i:s') : $param['create_time'];
        $images = $param['images'] === '' ? [] : json_decode($param['images'] , true);
        try {
            DB::beginTransaction();
            $id = ImageSubjectModel::insertGetId(array_unit($param , [
                'name' ,
                'user_id' ,
                'module_id' ,
                'category_id' ,
                'type' ,
                'subject_id' ,
                'tag' ,
                'thumb' ,
                'description' ,
                'weight' ,
                'view_count' ,
                'praise_count' ,
                'status' ,
                'fail_reason' ,
                'create_time' ,
            ]));
            foreach ($images as $v)
            {
                ImageModel::insertGetId([
                    'image_subject_id' => $id ,
                    'path' => $v
                ]);
            }
            DB::commit();
            return self::success();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function show(Base $context , $id , array $param = []): array
    {
        $res = ImageSubjectModel::find($id);
        if (empty($res)) {
            return self::error('图片专题不存在' , 404);
        }
        $res = ImageSubjectHandler::handle($res);
        return self::success($res);
    }

    public static function destroy(Base $context , $id , array $param = []): array
    {
        $count = ImageSubjectModel::delById($id);;
        return self::success($count);
    }

    public static function destroyAll(Base $context , array $ids , array $param = []): array
    {
        $count = ImageSubjectModel::delByIds($ids);;
        return self::success($count);
    }

    public static function destroyImages(Base $context , array $ids , array $param = []): array
    {
        $count = ImageModel::delByIds($ids);
        return self::success($count);
    }
}
