<?php


namespace App\Customize\api\admin_v1\action;

use App\Customize\api\admin_v1\handler\ImageSubjectHandler;
use App\Customize\api\admin_v1\model\CategoryModel;
use App\Customize\api\admin_v1\model\ImageModel;
use App\Customize\api\admin_v1\model\ImageSubjectCommentImageModel;
use App\Customize\api\admin_v1\model\ImageSubjectCommentModel;
use App\Customize\api\admin_v1\model\ImageSubjectModel;
use App\Customize\api\admin_v1\model\ModuleModel;
use App\Customize\api\admin_v1\model\RelationTagModel;
use App\Customize\api\admin_v1\model\SubjectModel;
use App\Customize\api\admin_v1\model\TagModel;
use App\Customize\api\admin_v1\model\UserModel;
use App\Customize\api\admin_v1\util\ImageSubjectUtil;
use App\Customize\api\admin_v1\util\ResourceUtil;
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
        $type_range     = array_keys(my_config('business.type_for_image_subject'));
        $status_range   = array_keys(my_config('business.status_for_image_subject'));

        $validator = Validator::make($param , [
            'type'          => ['sometimes' , Rule::in($type_range)] ,
            'status'        => ['sometimes' , 'integer' , Rule::in($status_range)] ,
            'user_id'       => 'sometimes|integer' ,
            'module_id'     => 'sometimes|integer' ,
            'category_id'   => 'sometimes|integer' ,
            'subject_id'    => 'sometimes|integer' ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $limit = $param['limit'] === '' ? my_config('app.limit') : $param['limit'];

        $paginator = ImageSubjectModel::index($param , $order , $limit);
        $paginator = ImageSubjectHandler::handlePaginator($paginator);

        return self::success('' , $paginator);
    }

    public static function update(Base $context , $id , array $param = []): array
    {
        $type_range     = array_keys(my_config('business.type_for_image_subject'));
        $status_range   = array_keys(my_config('business.status_for_image_subject'));

        $validator = Validator::make($param , [
//            'name'          => 'required' ,
            'user_id'       => 'required|integer' ,
            'module_id'     => 'required|integer' ,
            'category_id'   => 'required|integer' ,
            'subject_id'    => 'sometimes|integer' ,
            'type'          => ['required' , Rule::in($type_range)] ,
            'weight'        => 'sometimes|integer' ,
            'view_count'    => 'sometimes|integer' ,
            'praise_count'  => 'sometimes|integer' ,
            'status'        => ['required' , 'integer' , Rule::in($status_range)] ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $image_subject = ImageSubjectModel::find($id);
        if (empty($image_subject)) {
            return self::error('记录不存在' , '' , 404);
        }

        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('' , [
                'module_id' => '模块不存在'
            ]);
        }

        $category = CategoryModel::find($param['category_id']);
        if (empty($category)) {
            return self::error('' , [
                'category_id' => '分类不存在' ,
            ]);
        }

        $user = UserModel::find($param['user_id']);
        if (empty($user)) {
            return self::error('' , [
                'user_id' => '用户不存在'
            ]);
        }

        $subject = null;
        if ($param['type'] === 'pro') {
            $subject = SubjectModel::find($param['subject_id']);
            if (empty($subject)) {
                return self::error('' , [
                    'subject_id' => '专题不存在' ,
                ]);
            }
        }

        if ($param['status'] !== '' && $param['status'] == -1 && $param['fail_reason'] === '') {
            return self::error('' , [
                'fail_reason' => '请提供失败原因' ,
            ]);
        }

        $param['weight']        = $param['weight'] === '' ? 0 : $param['weight'];
        $param['create_time']   = $param['create_time'] === '' ? date('Y-m-d H:i:s') : $param['create_time'];
        $images                 = $param['images'] === '' ? [] : json_decode($param['images'] , true);
        $tags                   = $param['tags'] === '' ? [] : json_decode($param['tags'] , true);

        try {
            DB::beginTransaction();
            ImageSubjectModel::updateById($image_subject->id , array_unit($param , [
                'name' ,
                'user_id' ,
                'module_id' ,
                'category_id' ,
                'type' ,
                'subject_id' ,
                'thumb' ,
                'description' ,
                'weight' ,
                'view_count' ,
                'praise_count' ,
                'status' ,
                'fail_reason' ,
                'create_time' ,
            ]));
            ResourceUtil::used($param['thumb']);
            if ($image_subject->thumb !== $param['thumb']) {
                ResourceUtil::delete($image_subject->thumb);
            }
            $my_tags = RelationTagModel::getByRelationTypeAndRelationId('image_subject' , $image_subject->id);
            foreach ($tags as $v)
            {
                foreach ($my_tags as $v1)
                {
                    if ($v1->tag_id === $v) {
                        DB::rollBack();
                        return self::error('' , [
                            'tags' => '存在重复标签: name: ' . $v1->name . '; id: ' . $v1->tag_id ,
                        ]);
                    }
                }
                $tag = TagModel::find($v);
                if (empty($tag)) {
                    DB::rollBack();
                    return self::error('存在不存在的标签' , '' , 404);
                }
                RelationTagModel::insertGetId([
                    'relation_type' => 'image_subject' ,
                    'relation_id' => $image_subject->id ,
                    'tag_id' => $tag->id ,
                    'name' => $tag->name ,
                    'module_id' => $tag->module_id ,
                ]);
                // 针对该标签的计数要增加
                TagModel::updateById($tag->id , [
                    'count' => ++$tag->count
                ]);
            }
            foreach ($images as $v)
            {
                ImageModel::insertGetId([
                    'image_subject_id' => $image_subject->id ,
                    'path' => $v
                ]);
                ResourceUtil::used($v);
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
//            'name'      => 'required' ,
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
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('' , [
                'module_id' => '模块不存在'
            ]);
        }
        $category = CategoryModel::find($param['category_id']);
        if (empty($category)) {
            return self::error('' , [
                'category_id' => '分类不存在' ,
            ]);
        }
        $user = UserModel::find($param['user_id']);
        if (empty($user)) {
            return self::error('' , [
                'user_id' => '用户不存在'
            ]);
        }
        $subject = null;
        if ($param['type'] === 'pro') {
            $subject = SubjectModel::find($param['subject_id']);
            if (empty($subject)) {
                return self::error('' , [
                    'subject_id' => '专题不存在' ,
                ]);
            }
        }
        if ($param['status'] !== '' && $param['status'] == -1 && $param['fail_reason'] === '') {
            return self::error('' , [
                'fail_reason' => '请提供失败原因' ,
            ]);
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        $param['create_time'] = $param['create_time'] === '' ? date('Y-m-d H:i:s') : $param['create_time'];
        $images = $param['images'] === '' ? [] : json_decode($param['images'] , true);
        $tags = $param['tags'] === '' ? [] : json_decode($param['tags'] , true);
        try {
            DB::beginTransaction();
            $id = ImageSubjectModel::insertGetId(array_unit($param , [
                'name' ,
                'user_id' ,
                'module_id' ,
                'category_id' ,
                'type' ,
                'subject_id' ,
                'thumb' ,
                'description' ,
                'weight' ,
                'view_count' ,
                'praise_count' ,
                'status' ,
                'fail_reason' ,
                'create_time' ,
            ]));
            ResourceUtil::used($param['thumb']);
            foreach ($tags as $v)
            {
                $tag = TagModel::find($v);
                if (empty($tag)) {
                    DB::rollBack();
                    return self::error('存在不存在的标签' , '' , 404);
                }
                RelationTagModel::insertGetId([
                    'relation_type' => 'image_subject' ,
                    'relation_id' => $id ,
                    'tag_id' => $tag->id ,
                    'name' => $tag->name ,
                    'module_id' => $tag->module_id ,
                ]);
                // 针对该标签的计数要增加
                TagModel::updateById($tag->id , [
                    'count' => ++$tag->count
                ]);
            }
            foreach ($images as $v)
            {
                ImageModel::insertGetId([
                    'image_subject_id' => $id ,
                    'path' => $v
                ]);
                ResourceUtil::used($v);
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
            return self::error('记录不存在' , '' , 404);
        }
        $res = ImageSubjectHandler::handle($res);
        return self::success('' , $res);
    }

    public static function destroy(Base $context , $id , array $param = []): array
    {
        try {
            DB::beginTransaction();
            ImageSubjectUtil::delete($id);
            DB::commit();
            return self::success();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function destroyAll(Base $context , array $ids , array $param = []): array
    {
        try {
            DB::beginTransaction();
            foreach ($ids as $id)
            {
                ImageSubjectUtil::delete($id);
            }
            DB::commit();
            return self::success();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function destroyImages(Base $context , array $ids , array $param = []): array
    {
        $res = ImageModel::find($ids);
        try {
            DB::beginTransaction();
            foreach ($res as $v)
            {
                ResourceUtil::delete($v->path);
            }
            $count = ImageModel::destroy($ids);
            DB::commit();
            return self::success('' , $count);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // 删除单个标签
    public static function destroyTag(Base $context , array $param = []): array
    {
        $validator = Validator::make($param , [
            'image_subject_id' => 'required|integer' ,
            'tag_id'           => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $count = RelationTagModel::delByRelationTypeAndRelationIdAndTagId('image_subject' , $param['image_subject_id'] , $param['tag_id']);
        return self::success('' , $count);
    }
}
