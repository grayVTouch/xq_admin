<?php


namespace App\Http\Controllers\api\web_v1;


use App\Customize\api\web_v1\action\ImageSubjectWithAuthAction;
use App\Customize\api\web_v1\action\UserAction;
use function api\web_v1\error;
use function api\web_v1\success;

class User extends Base
{
    // 创建收藏夹
    public function createCollectionGroup()
    {
        $param = $this->request->post();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['name'] = $param['name'] ?? '';
        $res = UserAction::createCollectionGroup($this , $param);
        if ($res['code'] !== 0) {
            return error($res['data'], $res['code']);
        }
        return success($res['data']);
    }

    // 删除收藏夹
    public function destroyCollectionGroup($collection_group_id)
    {
        $res = UserAction::destroyCollectionGroup($this , $collection_group_id);
        if ($res['code'] !== 0) {
            return error($res['data'], $res['code']);
        }
        return success($res['data']);
    }

    // 删除收藏夹
    public function destroyAllCollectionGroup()
    {
        $collection_group_ids = $this->request->post('collection_group_ids' , []);
        $res = UserAction::destroyAllCollectionGroup($this , $collection_group_ids);
        if ($res['code'] !== 0) {
            return error($res['data'], $res['code']);
        }
        return success($res['data']);
    }
}
