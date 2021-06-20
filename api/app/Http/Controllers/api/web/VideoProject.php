<?php


namespace App\Http\Controllers\api\web;


use App\Customize\api\web\action\VideoProjectAction;
use function api\web\error;
use function api\web\success;

class VideoProject extends Base
{
    public function newest()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['limit']     = $param['limit'] ?? '';
        $res = VideoProjectAction::newest($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function hot()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['limit']     = $param['limit'] ?? '';
        $res = VideoProjectAction::hot($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function hotWithPager()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['limit']     = $param['limit'] ?? '';
        $res = VideoProjectAction::hotWithPager($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function newestWithPager()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['type']      = $param['type'] ?? '';
        $param['limit']     = $param['limit'] ?? '';
        $res = VideoProjectAction::newestWithPager($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    // 标签对应内容
    public function getByTagId()
    {
        $param = $this->request->query();

        $param['module_id'] = $param['module_id'] ?? '';
        $param['tag_id']      = $param['tag_id'] ?? '';
        $param['limit']     = $param['limit'] ?? '';

        $res = VideoProjectAction::getByTagId($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    // 标签对应内容
    public function getWithPagerByTagIds()
    {
        $param = $this->request->query();

        $param['module_id'] = $param['module_id'] ?? '';
        $param['tag_ids']   = $param['tag_ids'] ?? '';
        $param['mode']      = $param['mode'] ?? '';
        $param['limit']     = $param['limit'] ?? '';

        $res = VideoProjectAction::getWithPagerByTagIds($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    // 最火图片标签
    public function hotTags()
    {
        $param = $this->request->query();

        $param['module_id'] = $param['module_id'] ?? '';
        $param['type']      = $param['type'] ?? '';
        $param['limit']     = $param['limit'] ?? '';

        $res = VideoProjectAction::hotTags($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }

        return success($res['message'] , $res['data']);
    }

    public function hotTagsWithPager()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $param['value'] = $param['value'] ?? '';
        $res = VideoProjectAction::hotTagsWithPager($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function show($id)
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $res = VideoProjectAction::show($this , $id , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    // 图片专区分类
    public function category()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $res = VideoProjectAction::category($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    // 图片专区分类
    public function subject()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['value'] = $param['value'] ?? '';
        $res = VideoProjectAction::subject($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }


    public function index()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['category_ids'] = $param['category_ids'] ?? '';
        $param['video_series_ids'] = $param['video_series_ids'] ?? '';
        $param['video_company_ids'] = $param['video_company_ids'] ?? '';
        $param['tag_ids'] = $param['tag_ids'] ?? '';
        $param['order'] = $param['order'] ?? '';
        $param['value'] = $param['value'] ?? '';
        $param['mode'] = $param['mode'] ?? '';
        $res = VideoProjectAction::index($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function incrementViewCount(int $image_subject_id)
    {
        $res = VideoProjectAction::incrementViewCount($this , $image_subject_id);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function recommend(int $image_subject_id)
    {
        $param = $this->request->query();

        $param['type']  = $param['type'] ?? '';
        $param['limit'] = $param['limit'] ?? '';

        $res = VideoProjectAction::recommend($this , $image_subject_id , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function videosInRange(int $video_project_id)
    {
        $param = $this->request->query();
        $param['max'] = $param['max'] ?? '';
        $param['min'] = $param['min'] ?? '';
        $res = VideoProjectAction::videosInRange($this , $video_project_id , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function getByVideoSeriesId()
    {
        $param = $this->request->post();
        $param['video_project_id'] = $param['video_project_id'] ?? '';
        $param['video_series_id'] = $param['video_series_id'] ?? '';
        $res = VideoProjectAction::getByVideoSeriesId($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
