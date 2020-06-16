<?php


namespace App\Customize\api\admin_v1\action;

use App\Customize\api\admin_v1\handler\SubjectHandler;
use App\Customize\api\admin_v1\model\SubjectModel;
use App\Http\Controllers\api\admin_v1\Base;
use Illuminate\Support\Facades\Validator;
use function api\admin_v1\get_form_error;
use function api\admin_v1\my_config;
use function api\admin_v1\parse_order;
use function core\array_unit;

class SubjectAction extends Action
{
    public static function index(Base $context , array $param = [])
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $limit = $param['limit'] === '' ? my_config('app.limit') : $param['limit'];
        $paginator = SubjectModel::index($param , $order , $limit);
        $paginator = SubjectHandler::handlePaginator($paginator);
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
        $role = SubjectModel::find($id);
        if (empty($role)) {
            return self::error('关联不存在' , 404);
        }
        $param['attr'] = $param['attr'] === '' ? '{}' : $param['attr'];
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        SubjectModel::updateById($role->id , array_unit($param , [
            'name' ,
            'description' ,
            'thumb' ,
            'attr' ,
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
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        $id = SubjectModel::insertGetId(array_unit($param , [
            'name' ,
            'description' ,
            'thumb' ,
            'attr' ,
            'weight' ,
        ]));
        return self::success($id);
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $role = SubjectModel::find($id);
        if (empty($role)) {
            return self::error('关联主体不存在' , 404);
        }
        $role = SubjectModel::handle($role);
        return self::success($role);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $count = SubjectModel::delById($id);;
        return self::success($count);
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $count = SubjectModel::delByIds($ids);;
        return self::success($count);
    }

    public static function search(Base $context , $value , array $param = [])
    {
        if (empty($value)) {
            return self::error('请提供搜索值');
        }
        $res = SubjectModel::search($value);
        $res = SubjectHandler::handleAll($res);
        return self::success($res);
    }
}
