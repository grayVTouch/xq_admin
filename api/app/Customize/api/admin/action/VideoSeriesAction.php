<?php


namespace App\Customize\api\admin\action;

use App\Customize\api\admin\handler\VideoSeriesHandler;
use App\Customize\api\admin\handler\UserHandler;
use App\Customize\api\admin\model\VideoSeriesModel;
use App\Customize\api\admin\model\UserModel;
use App\Http\Controllers\api\admin\Base;
use Illuminate\Support\Facades\Validator;
use function api\admin\get_form_error;
use function api\admin\my_config;
use function api\admin\parse_order;
use function core\array_unit;
use function core\current_datetime;

class VideoSeriesAction extends Action
{
    public static function index(Base $context , array $param = [])
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $limit = $param['limit'] === '' ? my_config('app.limit') : $param['limit'];
        $paginator = VideoSeriesModel::index($param , $order , $limit);
        $paginator = VideoSeriesHandler::handlePaginator($paginator);
        return self::success('' , $paginator);
    }

    public static function update(Base $context , $id , array $param = [])
    {
        $validator = Validator::make($param , [
            'name' => 'required' ,
            'module_id' => 'required' ,
            'weight' => 'sometimes|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $res = VideoSeriesModel::find($id);
        if (empty($res)) {
            return self::error('关联不存在' , '' , 404);
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        VideoSeriesModel::updateById($res->id , array_unit($param , [
            'name' ,
            'weight' ,
            'module_id' ,
        ]));
        return self::success();
    }

    public static function store(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'name' => 'required' ,
            'weight' => 'sometimes|integer' ,
            'module_id' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        $param['created_at'] = current_datetime();
        $id = VideoSeriesModel::insertGetId(array_unit($param , [
            'name' ,
            'weight' ,
            'module_id' ,
            'created_at' ,
        ]));
        return self::success('' , $id);
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $res = VideoSeriesModel::find($id);
        if (empty($res)) {
            return self::error('关联主体不存在' , '' , 404);
        }
        $res = VideoSeriesModel::handle($res);
        return self::success('' , $res);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $count = VideoSeriesModel::destroy($id);
        return self::success('' , $count);
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $count = VideoSeriesModel::destroy($ids);
        return self::success('' , $count);
    }

    public static function search(Base $context , array $param = [])
    {
        if (empty($param['module_id'])) {
            return self::error('请提供 module_id');
        }
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = VideoSeriesModel::search($param['module_id'] , $param['value'] , $limit);
        $res = VideoSeriesHandler::handlePaginator($res);
        return self::success('' , $res);
    }
}
