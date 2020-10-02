<?php


namespace App\Http\Controllers\api\web;


use App\Customize\api\web\action\VideoSubjectAction;
use function api\web\error;
use function api\web\success;

class VideoSubject extends Base
{
    public function newest()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['limit']     = $param['limit'] ?? '';
        $res = VideoSubjectAction::newest($this , $param);
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
        $res = VideoSubjectAction::hot($this , $param);
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
        $res = VideoSubjectAction::hotWithPager($this , $param);
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
        $res = VideoSubjectAction::newestWithPager($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    // 标签对应内容
    public function getByTagId($tag_id)
    {
        $param = $this->request->query();

        $param['module_id'] = $param['module_id'] ?? '';
        $param['type']      = $param['type'] ?? '';
        $param['limit']     = $param['limit'] ?? '';

        $res = VideoSubjectAction::getByTagId($this , $tag_id , $param);
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

        $res = VideoSubjectAction::getWithPagerByTagIds($this , $param);
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

        $res = VideoSubjectAction::hotTags($this , $param);
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
        $res = VideoSubjectAction::hotTagsWithPager($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function show($id)
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $res = VideoSubjectAction::show($this , $id , $param);
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
        $res = VideoSubjectAction::category($this , $param);
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
        $res = VideoSubjectAction::subject($this , $param);
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
        $param['subject_ids'] = $param['subject_ids'] ?? '';
        $param['tag_ids'] = $param['tag_ids'] ?? '';
        $param['order'] = $param['order'] ?? '';
        $param['value'] = $param['value'] ?? '';
        $param['mode'] = $param['mode'] ?? '';
        $res = VideoSubjectAction::index($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function incrementViewCount(int $image_subject_id)
    {
        $res = VideoSubjectAction::incrementViewCount($this , $image_subject_id);
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

        $res = VideoSubjectAction::recommend($this , $image_subject_id , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function videosInRange(int $video_subject_id)
    {
        $param = $this->request->query();
        $param['max'] = $param['max'] ?? '';
        $param['min'] = $param['min'] ?? '';
        $res = VideoSubjectAction::videosInRange($this , $video_subject_id , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function videoSubjectsInSeries(int $video_series_id)
    {
        $param = $this->request->post();
        $param['video_subject_id'] = $param['video_subject_id'] ?? '';
        $res = VideoSubjectAction::videoSubjectsInSeries($this , $video_series_id , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
