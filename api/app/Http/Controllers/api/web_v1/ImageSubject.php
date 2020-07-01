<?php


namespace App\Http\Controllers\api\web_v1;


use App\Customize\api\web_v1\action\ImageSubjectAction;
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
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function hot()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = ImageSubjectAction::hot($this , $param);
        if ($res['code'] !== 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function hotWithPager()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = ImageSubjectAction::hotWithPager($this , $param);
        if ($res['code'] !== 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function newestWithPager()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = ImageSubjectAction::newestWithPager($this , $param);
        if ($res['code'] !== 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    // 标签对应内容
    public function getByTagId($tag_id)
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = ImageSubjectAction::getByTagId($this , $tag_id , $param);
        if ($res['code'] !== 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
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
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    // 最火图片标签
    public function hotTags()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = ImageSubjectAction::hotTags($this , $param);
        if ($res['code'] !== 0) {
            return error($res['data'], $res['code']);
        }
        return success($res['data']);
    }

    public function hotTagsWithPager()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = ImageSubjectAction::hotTagsWithPager($this , $param);
        if ($res['code'] !== 0) {
            return error($res['data'], $res['code']);
        }
        return success($res['data']);
    }

    public function show($id)
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $res = ImageSubjectAction::show($this , $id , $param);
        if ($res['code'] !== 0) {
            return error($res['data'], $res['code']);
        }
        return success($res['data']);
    }
}
