<?php


namespace App\Customize\api\admin_v1\action;

use App\Customize\api\admin_v1\handler\SubjectHandler;
use App\Customize\api\admin_v1\handler\UserHandler;
use App\Customize\api\admin_v1\model\SubjectModel;
use App\Customize\api\admin_v1\model\UserModel;
use App\Customize\api\admin_v1\util\ResourceUtil;
use App\Http\Controllers\api\admin_v1\Base;
use Exception;
use Illuminate\Support\Facades\DB;
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
            'name'      => 'required' ,
            'module_id' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $res = SubjectModel::find($id);
        if (empty($res)) {
            return self::error('关联不存在' , '' , 404);
        }
        $param['attr'] = $param['attr'] === '' ? '{}' : $param['attr'];
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        try {
            DB::beginTransaction();
            SubjectModel::updateById($res->id , array_unit($param , [
                'name' ,
                'description' ,
                'thumb' ,
                'attr' ,
                'weight' ,
                'module_id' ,
            ]));
            ResourceUtil::used($param['thumb']);
            if ($res->thumb !== $param['thumb']) {
                ResourceUtil::delete($res->thumb);
            }
            DB::commit();
            return self::success();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public static function store(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'name' => 'required' ,
            'module_id' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        try {
            DB::beginTransaction();
            $id = SubjectModel::insertGetId(array_unit($param , [
                'name' ,
                'description' ,
                'thumb' ,
                'attr' ,
                'weight' ,
                'module_id' ,
            ]));
            ResourceUtil::used($param['thumb']);
            DB::commit();
            return self::success('' , $id);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
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
        $res = SubjectModel::find($id);
        if (empty($res)) {
            return self::error('记录不存在' , '' , 404);
        }
        try {
            DB::beginTransaction();
            ResourceUtil::delete($res->thumb);
            $count = SubjectModel::destroy($res->id);
            DB::commit();
            return self::success('' , $count);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $res = SubjectModel::find($ids);
        try {
            DB::beginTransaction();
            foreach ($res as $v)
            {
                ResourceUtil::delete($v->thumb);
            }
            $count = SubjectModel::destroy($ids);
            DB::commit();
            return self::success('' , $count);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function search(Base $context , array $param = [])
    {
        if (empty($param['module_id'])) {
            return self::error('请提供 module_id');
        }
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = SubjectModel::search($param['module_id'] , $param['value'] , $limit);
        $res = SubjectHandler::handlePaginator($res);
        return self::success('' , $res);
    }
}
