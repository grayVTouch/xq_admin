<?php


namespace App\Http\Controllers\api\web_v1;


use App\Customize\api\web_v1\action\ImageSubjectAction;
use App\Customize\api\web_v1\action\UserAction;
use function api\web_v1\error;
use function api\web_v1\success;

class ImageSubject extends Base
{
    public function newest()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = ImageSubjectAction::newest($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function hot()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = ImageSubjectAction::hot($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function hotWithPager()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = ImageSubjectAction::hotWithPager($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function newestWithPager()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = ImageSubjectAction::newestWithPager($this , $param);
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
        $param['limit'] = $param['limit'] ?? '';
        $res = ImageSubjectAction::getByTagId($this , $tag_id , $param);
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
        $param['tag_ids'] = $param['tag_ids'] ?? '';
        $param['mode'] = $param['mode'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = ImageSubjectAction::getWithPagerByTagIds($this , $param);
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
        $param['limit'] = $param['limit'] ?? '';
        $res = ImageSubjectAction::hotTags($this , $param);
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
        $res = ImageSubjectAction::hotTagsWithPager($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function show($id)
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $res = ImageSubjectAction::show($this , $id , $param);
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
        $res = ImageSubjectAction::category($this , $param);
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
        $res = ImageSubjectAction::subject($this , $param);
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
        $res = ImageSubjectAction::index($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function incrementViewCount(int $image_subject_id)
    {
        $res = ImageSubjectAction::incrementViewCount($this , $image_subject_id);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function recommend(int $image_subject_id)
    {
        $param = $this->request->query();
        $param['limit'] = $param['limit'] ?? '';
        $res = ImageSubjectAction::recommend($this , $image_subject_id , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

}
