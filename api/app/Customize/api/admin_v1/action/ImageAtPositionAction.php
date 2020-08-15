<?php


namespace App\Customize\api\admin_v1\action;

use App\Customize\api\admin_v1\handler\ImageAtPositionHandler;
use App\Customize\api\admin_v1\model\ImageAtPositionModel;
use App\Customize\api\admin_v1\model\ModuleModel;
use App\Customize\api\admin_v1\model\PositionModel;
use App\Customize\api\admin_v1\util\ResourceUtil;
use App\Http\Controllers\api\admin_v1\Base;
use Exception;
use Illuminate\Support\Facades\DB;
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
        return self::success('' , $paginator);
    }

    public static function update(Base $context , $id , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id'     => 'required' ,
            'position_id'   => 'required' ,
            'path'          => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $res = ImageAtPositionModel::find($id);
        if (empty($res)) {
            return self::error('id对应记录不存在' , '' , 404);
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , '' , 404);
        }
        $position = PositionModel::find($param['position_id']);
        if (empty($position)) {
            return self::error('位置不存在' , '' , 404);
        }
        $param['module_id'] = $module->id;
        $param['platform'] = $position->platform;
        try {
            DB::beginTransaction();
            ImageAtPositionModel::updateById($res->id , array_unit($param , [
                'position_id' ,
                'platform' ,
                'path' ,
                'link' ,
                'module_id' ,
            ]));
            ResourceUtil::used($param['path']);
            if ($res->path !== $param['path']) {
                ResourceUtil::delete($res->path);
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
        $validator = Validator::make($param , [
            'position_id'   => 'required' ,
            'module_id'     => 'required' ,
            'path'          => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , '' , 404);
        }
        $position = PositionModel::find($param['position_id']);
        if (empty($position)) {
            return self::error('位置不存在' , '' , 404);
        }
        $param['module_id'] = $module->id;
        $param['platform'] = $position->platform;
        try {
            DB::beginTransaction();
            $id = ImageAtPositionModel::insertGetId(array_unit($param , [
                'position_id' ,
                'platform' ,
                'path' ,
                'link' ,
                'module_id' ,
            ]));
            ResourceUtil::used($param['path']);
            DB::commit();
            return self::success('' , $id);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        };
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $role = ImageAtPositionModel::find($id);
        if (empty($role)) {
            return self::error('id 对应记录不存在' , '' , 404);
        }
        $role = ImageAtPositionModel::handle($role);
        return self::success('' , $role);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $res = ImageAtPositionModel::find($id);
        if (empty($res)) {
            return self::error('待删除记录不存在' , '' , 404);
        }
        try {
            DB::beginTransaction();
            $count = ImageAtPositionModel::destroy($id);
            ResourceUtil::delete($res->path);
            DB::commit();
            return self::success($count);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        try {
            DB::beginTransaction();
            $res = ImageAtPositionModel::find($ids);
            foreach ($res as $v)
            {
                ResourceUtil::delete($v->path);
            }
            $count = ImageAtPositionModel::destroy($ids);
            DB::commit();
            return self::success($count);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function search(Base $context , $value , array $param = [])
    {
        if (empty($value)) {
            return self::error('请提供搜索值');
        }
        $res = ImageAtPositionModel::search($value);
        $res = ImageAtPositionHandler::handleAll($res);
        return self::success('' , $res);
    }
}
