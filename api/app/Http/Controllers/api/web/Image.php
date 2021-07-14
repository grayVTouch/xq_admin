<?php


namespace App\Http\Controllers\api\web;


use App\Customize\api\web\action\ImageAction;
use App\Customize\api\web\action\UserAction;
use function api\web\error;
use function api\web\success;

class Image extends Base
{
    public function newest()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['type']      = $param['type'] ?? '';
        $param['size']     = $param['size'] ?? '';
        $res = ImageAction::newest($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function hot()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['type']      = $param['type'] ?? '';
        $param['size']     = $param['size'] ?? '';
        $res = ImageAction::hot($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function hotWithPager()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['type']      = $param['type'] ?? '';
        $param['size']     = $param['size'] ?? '';
        $res = ImageAction::hotWithPager($this , $param);
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
        $param['size']     = $param['size'] ?? '';
        $res = ImageAction::newestWithPager($this , $param);
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
        $param['type']      = $param['type'] ?? '';
        $param['size']     = $param['size'] ?? '';
        $res = ImageAction::getByTagId($this , $param);
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
        $param['size']     = $param['size'] ?? '';

        $res = ImageAction::getWithPagerByTagIds($this , $param);
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
        $param['size']     = $param['size'] ?? '';

        $res = ImageAction::hotTags($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }

        return success($res['message'] , $res['data']);
    }

    public function hotTagsWithPager()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['size'] = $param['size'] ?? '';
        $param['value'] = $param['value'] ?? '';
        $res = ImageAction::hotTagsWithPager($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function show($id)
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $res = ImageAction::show($this , $id , $param);
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
        $res = ImageAction::category($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    // 图片专区分类
    public function imageSubject()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['value'] = $param['value'] ?? '';
        $res = ImageAction::imageSubject($this , $param);
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
        $param['image_subject_ids'] = $param['image_subject_ids'] ?? '';
        $param['tag_ids'] = $param['tag_ids'] ?? '';
        $param['order'] = $param['order'] ?? '';
        $param['size'] = $param['size'] ?? '';
        $param['value'] = $param['value'] ?? '';
        $param['mode'] = $param['mode'] ?? '';
        $res = ImageAction::index($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function incrementViewCount(int $image_subject_id)
    {
        $res = ImageAction::incrementViewCount($this , $image_subject_id);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function recommend(int $image_subject_id)
    {
        $param = $this->request->query();

        $param['type']  = $param['type'] ?? '';
        $param['size'] = $param['size'] ?? '';

        $res = ImageAction::recommend($this , $image_subject_id , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

}
