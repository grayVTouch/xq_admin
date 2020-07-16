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
        $res = SubjectModel::find($id);
        if (empty($res)) {
            return self::error('关联不存在' , '' , 404);
        }
        $param['attr'] = $param['attr'] === '' ? '{}' : $param['attr'];
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        SubjectModel::updateById($res->id , array_unit($param , [
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
        $id = SubjectModel::insertGetId(array_unit($param , [
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
        $res = SubjectModel::find($id);
        if (empty($res)) {
            return self::error('关联主体不存在' , '' , 404);
        }
        $res = SubjectModel::handle($res);
        return self::success('' , $res);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $count = SubjectModel::delById($id);
        return self::success('' , $count);
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $count = SubjectModel::delByIds($ids);
        return self::success('' , $count);
    }

    public static function search(Base $context , array $param = [])
    {
        if (empty($param['value'])) {
            return self::error('请提供搜索值');
        }
        if (empty($param['module_id'])) {
            return self::error('请提供 module_id');
        }
        $res = SubjectModel::search($param['module_id'] , $param['value'] , 20);
        $res = SubjectHandler::handleAll($res);
        return self::success('' , $res);
    }
}
