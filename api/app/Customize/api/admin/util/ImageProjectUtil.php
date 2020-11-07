<?php


namespace App\Customize\api\admin\util;


use App\Customize\api\admin\handler\ImageProjectHandler;
use App\Customize\api\admin\model\ImageModel;
use App\Customize\api\admin\model\ImageProjectCommentImageModel;
use App\Customize\api\admin\model\ImageProjectCommentModel;
use App\Customize\api\admin\model\ImageProjectModel;
use App\Customize\api\admin\model\RelationTagModel;

class ImageProjectUtil
{
    public static function delete(int $image_subject_id): bool
    {
        $res = ImageProjectModel::find($image_subject_id);
        if (empty($res)) {
            return false;
        }
        $res = ImageProjectHandler::handle($res , [
            'images'
        ]);
        ResourceUtil::delete($res->thumb);
        foreach ($res->images as $v)
        {
            ResourceUtil::delete($v->src);
        }
        $comment_images = ImageProjectCommentImageModel::getByImageProjectId($res->id);
        foreach ($comment_images as $v)
        {
            ResourceUtil::delete($v->src);
        }
        // 删除图片主题
        ImageProjectModel::destroy($res->id);
        // 删除图片主题相关的图片
        ImageModel::delByImageProjectId($res->id);
        // 删除图片主题相关的评论
        ImageProjectCommentModel::delByImageProjectId($res->id);
        // 删除图片主题相关评论对应的评论图片
        ImageProjectCommentImageModel::delByImageProjectId($res->id);
        // 删除图片对应的标签
        RelationTagModel::delByRelationTypeAndRelationId('image_project' , $res->id);
        return true;
    }
}
