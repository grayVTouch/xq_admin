<?php


namespace App\Http\Controllers\api\admin;


use App\Customize\api\admin\action\VideoSubtitleAction;
use function api\admin\error;
use function api\admin\success;

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
