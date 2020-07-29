<?php


namespace App\Customize\api\admin_v1\action;

use App\Customize\api\admin_v1\handler\VideoSubjectHandler;
use App\Customize\api\admin_v1\handler\UserHandler;
use App\Customize\api\admin_v1\model\VideoSubjectModel;
use App\Customize\api\admin_v1\model\UserModel;
use App\Http\Controllers\api\admin_v1\Base;
use Illuminate\Support\Facades\Validator;
use function api\admin_v1\get_form_error;
use function api\admin_v1\my_config;
use function api\admin_v1\parse_order;
use function core\array_unit;

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
        $validator = Validator::make($param , [
            'name' => 'required' ,
            'module_id' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error('表单错误，请检查' , get_form_error($validator));
        }
        $res = VideoSubjectModel::find($id);
        if (empty($res)) {
            return self::error('关联不存在' , '' , 404);
        }
        $param['attr'] = $param['attr'] === '' ? '{}' : $param['attr'];
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        VideoSubjectModel::updateById($res->id , array_unit($param , [
            'name' ,
            'description' ,
            'thumb' ,
            'attr' ,
            'weight' ,
            'module_id' ,
        ]));
        return self::success();
    }

    public static function store(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'name' => 'required' ,
            'module_id' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error('表单错误，请检查' , get_form_error($validator));
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        $id = VideoSubjectModel::insertGetId(array_unit($param , [
            'name' ,
            'description' ,
            'thumb' ,
            'attr' ,
            'weight' ,
            'module_id' ,
        ]));
        return self::success('' , $id);
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
        $count = VideoSubjectModel::delById($id);
        return self::success('' , $count);
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $count = VideoSubjectModel::delByIds($ids);
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
