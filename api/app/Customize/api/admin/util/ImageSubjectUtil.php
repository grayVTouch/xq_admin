<?php


namespace App\Customize\api\admin\util;


use App\Customize\api\admin\handler\ImageSubjectHandler;
use App\Customize\api\admin\model\ImageModel;
use App\Customize\api\admin\model\ImageSubjectCommentImageModel;
use App\Customize\api\admin\model\ImageSubjectCommentModel;
use App\Customize\api\admin\model\ImageSubjectModel;
use App\Customize\api\admin\model\RelationTagModel;

class ImageSubjectUtil
{
    public static function delete(int $image_subject_id): bool
    {
        $res = ImageSubjectModel::find($image_subject_id);
        if (empty($res)) {
            return false;
        }
        $res = ImageSubjectHandler::handle($res);
        ResourceUtil::delete($res->thumb);
        foreach ($res->images as $v)
        {
            ResourceUtil::delete($v->path);
        }
        $comment_images = ImageSubjectCommentImageModel::getByImageSubjectId($res->id);
        foreach ($comment_images as $v)
        {
            ResourceUtil::delete($v->path);
        }
        // 删除图片主题
        ImageSubjectModel::destroy($res->id);
        // 删除图片主题相关的图片
        ImageModel::delByImageSubjectId($res->id);
        // 删除图片主题相关的评论
        ImageSubjectCommentModel::delByImageSubjectId($res->id);
        // 删除图片主题相关评论对应的评论图片
        ImageSubjectCommentImageModel::delByImageSubjectId($res->id);
        // 删除图片对应的标签
        RelationTagModel::delByRelationTypeAndRelationId('image_subject' , $res->id);
        return true;
    }
}
