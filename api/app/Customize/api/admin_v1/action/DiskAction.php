<?php


namespace App\Customize\api\admin_v1\action;

use App\Customize\api\admin_v1\handler\DiskHandler;
use App\Customize\api\admin_v1\model\ModuleModel;
use App\Customize\api\admin_v1\model\DiskModel;
use App\Http\Controllers\api\admin_v1\Base;
use Core\Lib\File;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin_v1\get_form_error;
use function api\admin_v1\my_config;
use function api\admin_v1\parse_order;
use function core\array_unit;
use function core\current_time;

class DiskAction extends Action
{
    public static function index(Base $context , array $param = [])
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $limit = $param['limit'] === '' ? my_config('app.limit') : $param['limit'];
        $paginator = DiskModel::index($param , $order , $limit);
        $paginator = DiskHandler::handlePaginator($paginator);
        return self::success('' , $paginator);
    }

    public static function localUpdate(Base $context , $id , array $param = [])
    {
        $bool_range = array_keys(my_config('business.bool_for_int'));
        $os_range   = array_keys(my_config('business.os_for_disk'));

        $validator = Validator::make($param , [
            'default'   => ['sometimes' , 'integer' , Rule::in($bool_range)] ,
            'os'        => ['sometimes' , Rule::in($os_range)] ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $res = DiskModel::find($id);

        if (empty($res)) {
            return self::error('标签不存在' , '' , 404);
        }

        if (empty($param['prefix'])) {
            $repeat = DiskModel::findByExcludeIdAndPrefix($res->id , $param['prefix']);
            if (!empty($repeat)) {
                return self::error('表单错误' , [
                    'prefix' => '前缀已经存在，请重新输入' ,
                ]);
            }
        }

        if (!empty($param['path'])) {
            if (!File::isDir($param['path'])) {
                return self::error('表单错误' , [
                    'path' => '请提供真实有效的磁盘目录绝对路径' ,
                ]);
            }
        }

        $param['path']      = $param['path'] === '' ? $res->path : $param['path'];
        $param['os']        = $param['os'] === '' ? $res->os : $param['os'];
        $param['prefix']    = $param['prefix'] === '' ? $res->prefix : $param['prefix'];
        $param['default']   = $param['default'] === '' ? $res->default : $param['default'];

        try {
            DB::beginTransaction();

            DiskModel::updateById($res->id , array_unit($param , [
                'path' ,
                'os' ,
                'prefix' ,
                'default' ,
            ]));

            if ($param['default']) {
                DiskModel::setNotDefaultByExcludeId($res->id);
            }

            DB::commit();

            return self::success();
        } catch(Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public static function update(Base $context , $id , array $param = [])
    {
        $bool_range = array_keys(my_config('business.bool_for_int'));
        $os_range   = array_keys(my_config('business.os_for_disk'));

        $validator = Validator::make($param , [
            'path'      => 'required' ,
            'prefix'    => 'required' ,
            'default'   => ['required' , 'integer' , Rule::in($bool_range)] ,
            'os'        => ['required' , Rule::in($os_range)] ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $res = DiskModel::find($id);

        if (empty($res)) {
            return self::error('标签不存在' , '' , 404);
        }

        if (!File::isDir($param['path'])) {
            return self::error('表单错误' , [
                'path' => '请提供真实有效的磁盘目录绝对路径' ,
            ]);
        }

        $repeat = DiskModel::findByExcludeIdAndPrefix($res->id , $param['prefix']);

        if (!empty($repeat)) {
            return self::error('表单错误' , [
                'prefix' => '前缀已经存在，请重新输入' ,
            ]);
        }

        try {
            DB::beginTransaction();

            DiskModel::updateById($res->id , array_unit($param , [
                'path' ,
                'os' ,
                'prefix' ,
                'default' ,
            ]));

            if ($param['default']) {
                DiskModel::setNotDefaultByExcludeId($res->id);
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
        $bool_range = array_keys(my_config('business.bool_for_int'));
        $os_range   = array_keys(my_config('business.os_for_disk'));

        $validator = Validator::make($param , [
            'path'      => 'required' ,
            'prefix'    => 'required' ,
            'default'   => ['required' , 'integer' , Rule::in($bool_range)] ,
            'os'        => ['required' , Rule::in($os_range)] ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        if (!File::isDir($param['path'])) {
            return self::error('表单错误' , [
                'path' => '请提供真实有效的磁盘目录绝对路径' ,
            ]);
        }

        $repeat = DiskModel::findByPrefix($param['prefix']);
        if (!empty($repeat)) {
            return self::error('表单错误' , [
                'prefix' => '前缀已经存在，请重新输入' ,
            ]);
        }

        $param['create_time'] = current_time();
        try {
            DB::beginTransaction();

            $id = DiskModel::insertGetId(array_unit($param , [
                'path' ,
                'os' ,
                'prefix' ,
                'default' ,
                'create_time' ,
            ]));

            if ($param['default']) {
                DiskModel::setNotDefaultByExcludeId($id);
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
        $res = DiskModel::find($id);
        if (empty($res)) {
            return self::error('记录不存在' , '' , 404);
        }
        $res = DiskHandler::handle($res);
        return self::success('' , $res);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $count = DiskModel::destroy($id);
        return self::success('' , $count);
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $count = DiskModel::destroy($ids);
        return self::success('' , $count);
    }
}
