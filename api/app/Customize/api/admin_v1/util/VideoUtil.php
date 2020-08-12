<?php


namespace App\Customize\api\admin_v1\util;


use App\Customize\api\admin_v1\model\VideoCommentImageModel;
use App\Customize\api\admin_v1\model\VideoCommentModel;
use App\Customize\api\admin_v1\model\VideoModel;
use App\Customize\api\admin_v1\model\VideoSrcModel;

class VideoUtil
{
    public static function delete(int $id)
    {
        VideoModel::destroy($id);
        VideoSrcModel::delByVideoId($id);
        VideoCommentModel::delByVideoId($id);
        VideoCommentImageModel::delByVideoId($id);
    }
}
