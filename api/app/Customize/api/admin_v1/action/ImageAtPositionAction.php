<?php


namespace App\Customize\api\admin_v1\action;

use App\Customize\api\admin_v1\handler\ImageAtPositionHandler;
use App\Customize\api\admin_v1\model\ImageAtPositionModel;
use App\Customize\api\admin_v1\model\PositionModel;
use App\Http\Controllers\api\admin_v1\Base;
use Illuminate\Support\Facades\Validator;
use function api\admin_v1\get_form_error;
use function api\admin_v1\my_config;
use function api\admin_v1\parse_order;
use function core\array_unit;

class ImageAtPositionAction extends Action
{
    public static function index(Base $context , array $param = [])
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $limit = $param['limit'] === '' ? my_config('app.limit') : $param['limit'];
        $paginator = ImageAtPositionModel::index($param , $order , $limit);
        $paginator = ImageAtPositionHandler::handlePaginator($paginator);
        return self::success($paginator);
    }

    public static function update(Base $context , $id , array $param = [])
    {
        $validator = Validator::make($param , [
            'position_id' => 'required' ,
            'path' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $res = ImageAtPositionModel::find($id);
        if (empty($res)) {
            return self::error('id对应记录不存在' , 404);
        }
        $position = PositionModel::find($param['position_id']);
        if (empty($position)) {
            return self::error([
                'position_id' => '位置id对应记录不存在' ,
            ] , 404);
        }
        ImageAtPositionModel::updateById($res->id , array_unit($param , [
            'position_id' ,
            'name' ,
            'mime' ,
            'path' ,
            'link' ,
        ]));
        return self::success();
    }

    public static function store(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'position_id' => 'required' ,
            'path' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $position = PositionModel::find($param['position_id']);
        if (empty($position)) {
            return self::error([
                'position_id' => '位置id对应记录不存在' ,
            ] , 404);
        }
        $id = ImageAtPositionModel::insertGetId(array_unit($param , [
            'position_id' ,
            'name' ,
            'mime' ,
            'path' ,
            'link' ,
        ]));
        return self::success($id);
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $role = ImageAtPositionModel::find($id);
        if (empty($role)) {
            return self::error('id 对应记录不存在' , 404);
        }
        $role = ImageAtPositionModel::handle($role);
        return self::success($role);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $count = ImageAtPositionModel::delById($id);
        return self::success($count);
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $count = ImageAtPositionModel::delByIds($ids);
        return self::success($count);
    }

    public static function search(Base $context , $value , array $param = [])
    {
        if (empty($value)) {
            return self::error('请提供搜索值');
        }
        $res = ImageAtPositionModel::search($value);
        $res = ImageAtPositionHandler::handleAll($res);
        return self::success($res);
    }
}
