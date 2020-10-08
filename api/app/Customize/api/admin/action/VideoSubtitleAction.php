<?php


namespace App\Customize\api\admin\action;

use App\Customize\api\admin\model\TagModel;
use App\Customize\api\admin\model\VideoSubtitleModel;
use App\Customize\api\admin\util\ResourceUtil;
use App\Http\Controllers\api\admin\Base;
use Exception;
use Illuminate\Support\Facades\DB;

class VideoSubtitleAction extends Action
{
    public static function destroy(Base $context , $id , array $param = [])
    {
        $res = VideoSubtitleModel::find($id);
        if (empty($res)) {
            return self::error('记录未找到' , '' , 404);
        }
        try {
            DB::beginTransaction();
            $count = VideoSubtitleModel::destroy($res->id);
            ResourceUtil::delete($res->src);
            DB::commit();
            return self::success('操作成功' , $count);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        try {
            DB::beginTransaction();
            $res = VideoSubtitleModel::find($ids);
            foreach ($res as $v)
            {
                VideoSubtitleModel::destroy($v->id);
                ResourceUtil::delete($v->src);
            }
            DB::commit();
            return self::success('操作成功');
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
