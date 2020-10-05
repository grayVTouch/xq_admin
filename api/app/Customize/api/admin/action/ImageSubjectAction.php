<?php


namespace App\Customize\api\admin\action;

use App\Customize\api\admin\handler\ImageSubjectHandler;
use App\Customize\api\admin\job\ImageSubjectResourceHandleJob;
use App\Customize\api\admin\model\CategoryModel;
use App\Customize\api\admin\model\ImageModel;
use App\Customize\api\admin\model\ImageSubjectCommentImageModel;
use App\Customize\api\admin\model\ImageSubjectCommentModel;
use App\Customize\api\admin\model\ImageSubjectModel;
use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\RelationTagModel;
use App\Customize\api\admin\model\SubjectModel;
use App\Customize\api\admin\model\TagModel;
use App\Customize\api\admin\model\UserModel;
use App\Customize\api\admin\util\FileUtil;
use App\Customize\api\admin\util\ImageSubjectUtil;
use App\Customize\api\admin\util\ResourceUtil;
use App\Http\Controllers\api\admin\Base;
use Core\Lib\File;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin\get_form_error;
use function api\admin\my_config;
use function api\admin\my_config_keys;
use function api\admin\parse_order;
use function core\array_unit;
use function core\current_datetime;

class ImageSubjectAction extends Action
{
    public static function index(Base $context , array $param = []): array
    {
        $type_range     = my_config_keys('business.type_for_image_subject');
        $status_range   = my_config_keys('business.status_for_image_subject');

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
        $type_range     = my_config_keys('business.type_for_image_subject');
        $status_range   = my_config_keys('business.status_for_image_subject');
        $validator = Validator::make($param , [
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
        if ($param['type'] === 'pro') {
            if ($param['name'] === '') {
                return self::error('名称尚未提供' , ['name' => '名称尚未提供']);
            }
            if (ImageSubjectModel::findByNameAndExcludeId($param['name'] , $image_subject->id)) {
                return self::error('名称已经被使用' , ['name' => '名称已经被使用']);
            }
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , ['module_id' => '模块不存在']);
        }
        $category = CategoryModel::find($param['category_id']);
        if (empty($category)) {
            return self::error('分类不存在' , ['category_id' => '分类不存在']);
        }
        $user = UserModel::find($param['user_id']);
        if (empty($user)) {
            return self::error('用户不存在' , ['user_id' => '用户不存在']);
        }
        $subject = null;
        if ($param['type'] === 'pro') {
            $subject = SubjectModel::find($param['subject_id']);
            if (empty($subject)) {
                return self::error('专题不存在' , ['subject_id' => '专题不存在']);
            }
        }
        if ($param['status'] !== '' && $param['status'] == -1 && $param['fail_reason'] === '') {
            return self::error('请提供失败原因' , ['fail_reason' => '请提供失败原因']);
        }
        $datetime               = current_datetime();
        $param['weight']        = $param['weight'] === '' ? 0 : $param['weight'];
        $images                 = $param['images'] === '' ? [] : json_decode($param['images'] , true);
        $tags                   = $param['tags'] === '' ? [] : json_decode($param['tags'] , true);

        /**
         * 建立图片目录
         * 移动图片到指定的目录
         */
        $disk       = my_config('app.disk');
        $save_dir   = '';
        $prefix     = '';
        if ($disk === 'local') {
            $prefix             = FileUtil::prefix();
            $source_save_dir    = '';
            $target_save_dir    = '';
            if ($param['type'] === 'pro') {
                $source_save_dir    = FileUtil::generateRealPathByRelativePathWithoutPrefix($image_subject->name);
                $target_save_dir    = FileUtil::generateRealPathByRelativePathWithoutPrefix($param['name']);
            } else {
                $dirname            = my_config('app.dir')['image'];
                $date_string        = date('Ymd' , strtotime($image_subject->created_at));
                $source_save_dir    = FileUtil::generateRealPathByRelativePathWithoutPrefix($dirname . '/' . $date_string);
                $target_save_dir    = FileUtil::generateRealPathByRelativePathWithoutPrefix($dirname . '/' . date('Ymd'));
            }
            if (!File::exists($source_save_dir)) {
                File::cDir($save_dir , 0755 , true);
            }
            if ($source_save_dir !== $target_save_dir) {
                File::move($source_save_dir , $target_save_dir);
            }
            $save_dir = $target_save_dir;
        }
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
                'updated_at' ,
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
                    'relation_id'   => $image_subject->id ,
                    'tag_id'        => $tag->id ,
                    'name'          => $tag->name ,
                    'module_id'     => $tag->module_id ,
                    'updated_at'    => $datetime ,
                    'created_at'    => $datetime ,
                ]);
                // 针对该标签的计数要增加
                TagModel::updateById($tag->id , [
                    'count' => ++$tag->count
                ]);
            }
            foreach ($images as $v)
            {
                ImageModel::insertGetId([
                    'image_subject_id'  => $image_subject->id ,
                    'src'               => $v ,
                    'updated_at'        => $datetime ,
                    'created_at'        => $datetime ,
                ]);
                ResourceUtil::used($v);
            }
            DB::commit();
            // 图片迁移
            ImageSubjectResourceHandleJob::dispatch($id , $prefix , $save_dir);
            return self::success('操作成功');
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function store(Base $context , array $param = []): array
    {
        $type_range     = my_config_keys('business.type_for_image_subject');
        $status_range   = my_config_keys('business.status_for_image_subject');
        $validator = Validator::make($param , [
//            'name'      => 'required' ,
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
        if ($param['type'] === 'pro') {
            if ($param['name'] === '') {
                return self::error('名称尚未提供' , ['name' => '名称尚未提供']);
            }
            if (ImageSubjectModel::findByName($param['name'])) {
                return self::error('名称已经被使用' , ['name' => '名称已经被使用']);
            }
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , ['module_id' => '模块不存在']);
        }
        $category = CategoryModel::find($param['category_id']);
        if (empty($category)) {
            return self::error('分类不存在' , ['category_id' => '分类不存在']);
        }
        $user = UserModel::find($param['user_id']);
        if (empty($user)) {
            return self::error('用户不存在' , ['user_id' => '用户不存在']);
        }
        $subject = null;
        if ($param['type'] === 'pro') {
            $subject = SubjectModel::find($param['subject_id']);
            if (empty($subject)) {
                return self::error('专题不存在' , ['subject_id' => '专题不存在']);
            }
        }
        if ($param['status'] !== '' && $param['status'] == -1 && $param['fail_reason'] === '') {
            return self::error('请提供失败原因' , ['fail_reason' => '请提供失败原因']);
        }
        $datetime               = current_datetime();
        $param['weight']        = $param['weight'] === '' ? 0 : $param['weight'];
        $param['created_at']    = $param['created_at'] === '' ? $datetime : $param['created_at'];
        $images                 = $param['images'] === '' ? [] : json_decode($param['images'] , true);
        $tags                   = $param['tags'] === '' ? [] : json_decode($param['tags'] , true);

        /**
         * 建立图片目录
         * 移动图片到指定的目录
         */
        $disk       = my_config('app.disk');
        $save_dir   = '';
        $prefix     = '';
        if ($disk === 'local') {
            $prefix = FileUtil::prefix();
            if ($param['type'] === 'pro') {
                $save_dir = FileUtil::generateRealPathByRelativePathWithoutPrefix($param['name']);
            } else {
                $dirname = my_config('app.dir')['image'];
                $save_dir = FileUtil::generateRealPathByRelativePathWithoutPrefix($dirname . '/' . date('Ymd'));
            }
            if (!File::exists($save_dir)) {
                File::cDir($save_dir , 0755 , true);
            }
        }
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
                'created_at' ,
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
                    'relation_type'     => 'image_subject' ,
                    'relation_id'       => $id ,
                    'tag_id'            => $tag->id ,
                    'name'              => $tag->name ,
                    'module_id'         => $tag->module_id ,
                    'updated_at'        => $datetime ,
                    'created_at'        => $datetime ,
                ]);
                // 针对该标签的计数要增加
                TagModel::updateById($tag->id , [
                    'count' => ++$tag->count
                ]);
            }
            foreach ($images as $v)
            {
                ImageModel::insertGetId([
                    'image_subject_id'  => $id ,
                    'src'               => $v ,
                    'updated_at'        => $datetime ,
                    'created_at'        => $datetime ,
                ]);
                ResourceUtil::used($v);
            }
            DB::commit();
            // 图片迁移
            ImageSubjectResourceHandleJob::dispatch($id , $prefix , $save_dir);
            return self::success('操作成功');
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
            return self::success('操作成功');
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
            return self::success('操作成功');
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
