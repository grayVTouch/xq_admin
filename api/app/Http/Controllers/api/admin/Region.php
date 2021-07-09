<?php


namespace App\Http\Controllers\api\admin;


use App\Customize\api\admin\action\RegionAction;
use function api\admin\error;
use function api\admin\success;

class Region extends Base
{
    public function country()
    {
        $res = RegionAction::country($this);

        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }

        return success($res['message'] , $res['data']);
    }

    public function china()
    {

    }

    // 中国省份
    public function province()
    {

    }

    // 中国城市
    public function city(int $province_id)
    {

    }

    // 中国区域
    public function area(int $city_id)
    {

    }

    // 内容搜索
    public function search()
    {
        $param = $this->request->query();
        $param['value'] = $param['value'] ?? '';
        $param['type']  = $param['type'] ?? '';
        $param['size']  = $param['size'] ?? '';
        $res = RegionAction::search($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
