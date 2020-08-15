<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\action\VideoSubtitleAction;
use function api\admin_v1\error;
use function api\admin_v1\success;

class VideoSubtitle extends Base
{

    public function destroy(int $id)
    {
        $res = VideoSubtitleAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = VideoSubtitleAction::destroyAll($this , $ids);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
