<?php


namespace App\Http\Controllers\api\web_v1;


use App\Customize\api\web_v1\action\CategoryAction;
use function api\web_v1\error;
use function api\web_v1\success;

class Category extends Base
{
    public function all()
    {
        $res = CategoryAction::all($this);
        if ($res['code'] !== 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }
}
