<?php


namespace App\Customize\api\admin\action;

use App\Customize\api\admin\handler\VideoSubjectHandler;
use App\Customize\api\admin\handler\UserHandler;
use App\Customize\api\admin\model\CategoryModel;
use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\RelationTagModel;
use App\Customize\api\admin\model\TagModel;
use App\Customize\api\admin\model\VideoCompanyModel;
use App\Customize\api\admin\model\VideoSeriesModel;
use App\Customize\api\admin\model\VideoSubjectModel;
use App\Customize\api\admin\model\UserModel;
use App\Customize\api\admin\util\ResourceUtil;
use App\Http\Controllers\api\admin\Base;
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

class VideoSubjectAction extends Action
{
    public static function index(Base $context , array $param = [])
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $limit = $param['limit'] === '' ? my_config('app.limit') : $param['limit'];
        $paginator = VideoSubjectModel::index($param , $order , $limit);
        $paginator = VideoSubjectHandler::handlePaginator($paginator);
        return self::success('' , $paginator);
    }

    public static function update(Base $context , $id , array $param = [])
    {
        $status_range = my_config_keys('business.status_for_video_subject');
        $validator = Validator::make($param , [
            'name'          => 'required' ,
            'release_time'  => 'sometimes|date_format:Y-m-d' ,
            'end_time'      => 'sometimes|date_format:Y-m-d' ,
            'status'        => ['required' , Rule::in($status_range)] ,
            'count'         => 'sometimes|integer' ,
            'weight'        => 'sometimes|integer' ,
            'video_series_id'   => 'sometimes|integer' ,
            'video_company_id'  => 'sometimes|integer' ,
            'module_id'         => 'required|integer' ,
            'category_id'       => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $video_subject = VideoSubjectModel::find($id);
        if (empty($video_subject)) {
            return self::error('视频专题不存在' , '' , 404);
        }

        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('' , [
                'module_id' => '模块不存在' ,
            ]);
        }

        $category = CategoryModel::find($param['category_id']);
        if (empty($category)) {
            return self::error('' , [
                'category_id' => '分类不存在' ,
            ]);
        }

        $video_series = null;
        if (!empty($param['video_series_id'])) {
            $video_series = VideoSeriesModel::find($param['video_series_id']);
            if (empty($video_series)) {
                return self::error('' , [
                    'video_series_id' => '视频系列不存在' ,
                ]);
            }
        }

        $video_company = null;
        if (!empty($param['video_company_id'])) {
            $video_company = VideoCompanyModel::find($param['video_company_id']);
            if (empty($video_company)) {
                return self::error('' , [
                    'video_company_id' => '视频制作公司不存在' ,
                ]);
            }
        }

        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        $param['updated_at'] = current_datetime();
        $tags = $param['tags'] === '' ? [] : json_decode($param['tags'] , true);
        try {
            DB::beginTransaction();
            VideoSubjectModel::updateById($video_subject->id , array_unit($param , [
                'name' ,
                'thumb' ,
                'score' ,
                'release_time' ,
                'end_time' ,
                'status' ,
                'count' ,
                'description' ,
                'video_series_id' ,
                'video_company_id' ,
                'category_id' ,
                'module_id' ,
                'weight' ,
//                'updated_at' ,
            ]));
            ResourceUtil::used($param['thumb']);
            if ($video_subject->thumb !== $param['thumb']) {
                ResourceUtil::delete($video_subject->thumb);
            }
            $my_tags = RelationTagModel::getByRelationTypeAndRelationId('image_subject' , $video_subject->id);
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
                    'relation_type' => 'video_subject' ,
                    'relation_id' => $video_subject->id ,
                    'tag_id' => $tag->id ,
                    'name' => $tag->name ,
                    'module_id' => $tag->module_id ,
                ]);
                // 针对该标签的计数要增加
                TagModel::updateById($tag->id , [
                    'count' => ++$tag->count
                ]);
            }
            DB::commit();
            return self::success();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function store(Base $context , array $param = [])
    {
        $status_range = my_config_keys('business.status_for_video_subject');
        $validator = Validator::make($param , [
            'name'              => 'required' ,
            'release_time'      => 'sometimes|date_format:Y-m-d' ,
            'end_time'          => 'sometimes|date_format:Y-m-d' ,
            'status'            => ['required' , Rule::in($status_range)] ,
            'count'             => 'sometimes|integer' ,
            'weight'            => 'sometimes|integer' ,
            'video_series_id'   => 'sometimes|integer' ,
            'video_company_id'  => 'sometimes|integer' ,
            'module_id'         => 'required|integer' ,
            'category_id'         => 'required|integer' ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('' , [
                'module_id' => '模块不存在' ,
            ]);
        }

        $video_series = null;
        if (!empty($param['video_series_id'])) {
            $video_series = VideoSeriesModel::find($param['video_series_id']);
            if (empty($video_series)) {
                return self::error('' , [
                    'video_series_id' => '视频系列不存在' ,
                ]);
            }
        }

        $video_company = null;
        if (!empty($param['video_company_id'])) {
            $video_company = VideoCompanyModel::find($param['video_company_id']);
            if (empty($video_company)) {
                return self::error('' , [
                    'video_company_id' => '视频制作公司不存在' ,
                ]);
            }
        }

        $category = CategoryModel::find($param['category_id']);
        if (empty($category)) {
            return self::error('' , [
                'category_id' => '分类不存在' ,
            ]);
        }

        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        $param['created_at'] = current_datetime();
        $tags = $param['tags'] === '' ? [] : json_decode($param['tags'] , true);
        try {
            DB::beginTransaction();
            $id = VideoSubjectModel::insertGetId(array_unit($param, [
                'name',
                'thumb',
                'score',
                'release_time',
                'end_time',
                'status',
                'count',
                'description',
                'video_series_id',
                'video_company_id',
                'category_id',
                'module_id',
                'weight',
                'created_at',
            ]));
            ResourceUtil::used($param['thumb']);
            foreach ($tags as $v) {
                $tag = TagModel::find($v);
                if (empty($tag)) {
                    DB::rollBack();
                    return self::error('存在不存在的标签', '', 404);
                }
                RelationTagModel::insertGetId([
                    'relation_type' => 'video_subject',
                    'relation_id' => $id,
                    'tag_id' => $tag->id,
                    'name' => $tag->name,
                    'module_id' => $tag->module_id,
                ]);
                // 针对该标签的计数要增加
                TagModel::updateById($tag->id, [
                    'count' => ++$tag->count
                ]);
            }
            DB::commit();
            return self::success();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $res = VideoSubjectModel::find($id);
        if (empty($res)) {
            return self::error('关联主体不存在' , '' , 404);
        }
        $res = VideoSubjectModel::handle($res);
        return self::success('' , $res);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $res = VideoSubjectModel::find($id);
        if (empty($res)) {
            return self::error('记录不存在' , '' , 404);
        }
        try {
            DB::beginTransaction();
            ResourceUtil::delete($res->thumb);
            VideoSubjectModel::destroy($res->id);
            RelationTagModel::delByRelationTypeAndRelationId('video_subject' , $res->id);
            DB::commit();
            return self::success('');
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $res = VideoSubjectModel::find($ids);
        try {
            DB::beginTransaction();
            foreach ($res as $v)
            {
                ResourceUtil::delete($v->thumb);
                RelationTagModel::delByRelationTypeAndRelationId('video_subject' , $v->id);
                VideoSubjectModel::destroy($v->id);
            }
            DB::commit();
            return self::success();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // 删除单个标签
    public static function destroyTag(Base $context , array $param = []): array
    {
        $validator = Validator::make($param , [
            'video_subject_id' => 'required|integer' ,
            'tag_id'           => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $count = RelationTagModel::delByRelationTypeAndRelationIdAndTagId('video_subject' , $param['video_subject_id'] , $param['tag_id']);
        return self::success('' , $count);
    }

    public static function search(Base $context , array $param = [])
    {
        if (empty($param['module_id'])) {
            return self::error('请提供 module_id');
        }
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = VideoSubjectModel::search($param['module_id'] , $param['value'] , $limit);
        $res = VideoSubjectHandler::handlePaginator($res);
        return self::success('' , $res);
    }
}
