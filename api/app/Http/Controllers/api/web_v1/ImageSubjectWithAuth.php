<?php


namespace App\Http\Controllers\api\web_v1;


use App\Customize\api\web_v1\action\ImageSubjectWithAuthAction;
use function api\web_v1\error;
use function api\web_v1\success;

class ImageSubjectWithAuth extends Base
{
    // 收藏 & 取消收藏
    public function collectionHandle($image_subject_id)
    {
        $param = $this->request->post();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['action']    = $param['action'] ?? '';
        $param['collection_group_id'] = $param['collection_group_id'] ?? '';
        $res = ImageSubjectWithAuthAction::collectionHandle($this, $image_subject_id , $param);
        if ($res['code'] !== 0) {
            return error($res['data'], $res['code']);
        }
        return success($res['data']);
    }


}
