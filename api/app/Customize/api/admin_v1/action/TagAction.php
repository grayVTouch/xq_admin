<?php


namespace App\Customize\api\admin_v1\action;

use App\Customize\api\admin_v1\handler\TagHandler;
use App\Customize\api\admin_v1\model\TagModel;
use App\Http\Controllers\api\admin_v1\Base;
use Illuminate\Support\Facades\Validator;
use function api\admin_v1\get_form_error;
use function api\admin_v1\my_config;
use function api\admin_v1\parse_order;
use function core\array_unit;

class TagAction extends Action
{
    public static function index(Base $context , array $param = [])
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $limit = $param['limit'] === '' ? my_config('app.limit') : $param['limit'];
        $paginator = TagModel::index($param , $order , $limit);
        $paginator = TagHandler::handlePaginator($paginator);
        return self::success($paginator);
    }

    public static function update(Base $context , $id , array $param = [])
    {
        $validator = Validator::make($param , [
            'name' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $role = TagModel::find($id);
        if (empty($role)) {
            return self::error('标签不存在' , 404);
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        TagModel::updateById($role->id , array_unit($param , [
            'name' ,
            'weight' ,
        ]));
        return self::success();
    }

    public static function store(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'name' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $tag = TagModel::getByName($param['name']);
        if (!empty($tag)) {
            return self::error('标签已经存在');
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        $id = TagModel::insertGetId(array_unit($param , [
            'name' ,
            'weight' ,
        ]));
        return self::success($id);
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $role = TagModel::find($id);
        if (empty($role)) {
            return self::error('标签不存在' , 404);
        }
        $role = TagModel::handle($role);
        return self::success($role);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $count = TagModel::delById($id);
        return self::success($count);
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $count = TagModel::delByIds($ids);
        return self::success($count);
    }

    public static function search(Base $context , $value , array $param = [])
    {
        if (empty($value)) {
            return self::error('请提供搜索值');
        }
        $res = TagModel::search($value);
        $res = TagHandler::handleAll($res);
        return self::success($res);
    }

    public static function top(Base $context , array $param = [])
    {
        $res = TagModel::top(10);
        $res = TagHandler::handleAll($res);
        return self::success($res);
    }
}
