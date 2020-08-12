<?php


namespace App\Customize\api\admin_v1\action;

use App\Customize\api\admin_v1\handler\VideoCompanyHandler;
use App\Customize\api\admin_v1\model\RegionModel;
use App\Customize\api\admin_v1\model\VideoCompanyModel;
use App\Http\Controllers\api\admin_v1\Base;
use Illuminate\Support\Facades\Validator;
use function api\admin_v1\get_form_error;
use function api\admin_v1\my_config;
use function api\admin_v1\parse_order;
use function core\array_unit;
use function core\current_time;

class VideoCompanyAction extends Action
{
    public static function index(Base $context , array $param = [])
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $limit = $param['limit'] === '' ? my_config('app.limit') : $param['limit'];
        $paginator = VideoCompanyModel::index($param , $order , $limit);
        $paginator = VideoCompanyHandler::handlePaginator($paginator);
        return self::success('' , $paginator);
    }

    public static function update(Base $context , $id , array $param = [])
    {
        $validator = Validator::make($param , [
            'name'          => 'required' ,
            'module_id'     => 'required|integer' ,
            'country_id'    => 'sometimes|integer' ,
            'weight'        => 'sometimes|integer' ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $res = VideoCompanyModel::find($id);

        if (empty($res)) {
            return self::error('记录不存在' , '' , 404);
        }

        $country = RegionModel::find($param['country_id']);

        if (empty($country)) {
            return self::error('表单错误' , [
                'country_id' => '国家不存在' ,
            ]);
        }

        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        $param['country'] = $country->name;

        VideoCompanyModel::updateById($res->id , array_unit($param , [
            'name' ,
            'thumb' ,
            'description' ,
            'country_id' ,
            'country' ,
            'weight' ,
            'module_id' ,
        ]));

        return self::success();
    }

    public static function store(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'name'          => 'required' ,
            'module_id'     => 'required|integer' ,
            'country_id'    => 'sometimes|integer' ,
            'weight'        => 'sometimes|integer' ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $country = RegionModel::find($param['country_id']);

        if (empty($country)) {
            return self::error('表单错误' , [
                'country_id' => '国家不存在' ,
            ]);
        }

        $param['weight']        = $param['weight'] === '' ? 0 : $param['weight'];
        $param['create_time']   = current_time();
        $param['country']       = $country->name;

        $id = VideoCompanyModel::insertGetId(array_unit($param , [
            'name' ,
            'thumb' ,
            'description' ,
            'country_id' ,
            'country' ,
            'weight' ,
            'module_id' ,
            'create_time' ,
        ]));
        return self::success('' , $id);
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $res = VideoCompanyModel::find($id);
        if (empty($res)) {
            return self::error('关联主体不存在' , '' , 404);
        }
        $res = VideoCompanyModel::handle($res);
        return self::success('' , $res);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $count = VideoCompanyModel::destroy($id);
        return self::success('' , $count);
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $count = VideoCompanyModel::destroy($ids);
        return self::success('' , $count);
    }

    public static function search(Base $context , array $param = [])
    {
        if (empty($param['module_id'])) {
            return self::error('请提供 module_id');
        }
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = VideoCompanyModel::search($param['module_id'] , $param['value'] , $limit);
        $res = VideoCompanyHandler::handlePaginator($res);
        return self::success('' , $res);
    }
}
