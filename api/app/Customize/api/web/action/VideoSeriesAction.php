<?php


namespace App\Customize\api\web\action;


use App\Customize\api\web\handler\VideoSeriesHandler;
use App\Customize\api\web\model\ModuleModel;
use App\Customize\api\web\model\VideoSeriesModel;
use App\Http\Controllers\api\web\Base;
use Illuminate\Support\Facades\Validator;
use function api\web\my_config;
use function api\web\parse_order;

class VideoSeriesAction extends Action
{
    public static function index(Base $context, array $param = []): array
    {
        $validator = Validator::make($param, [
            'module_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在', '', 404);
        }
        $order = $param['order'] === '' ? null : parse_order($param['order']);
        $size = $param['size'] === '' ? my_config('app.limit') : $param['size'];
        $res = VideoSeriesModel::index(null, $param, $order, $size);
        $res = VideoSeriesHandler::handlePaginator($res);
        return self::success('', $res);
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , '' , 404);
        }
        $video_series = VideoSeriesModel::find($id);
        if (empty($video_series)) {
            return self::error('系列不存在' , '' , 404);
        }
        $video_series = VideoSeriesHandler::handle($video_series);
        return self::success('' , $video_series);
    }


}
