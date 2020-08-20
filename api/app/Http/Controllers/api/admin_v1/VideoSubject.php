<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\action\VideoSubjectAction;
use function api\admin_v1\error;
use function api\admin_v1\success;

class VideoSubject extends Base
{
    public function index()
    {
        $param = $this->request->query();

        $param['id']               = $param['id'] ?? '';
        $param['name']             = $param['name'] ?? '';
        $param['module_id']        = $param['module_id'] ?? '';
        $param['video_series_id']  = $param['video_series_id'] ?? '';
        $param['video_company_id'] = $param['video_company_id'] ?? '';
        $param['order']            = $param['order'] ?? '';
        $param['limit']            = $param['limit'] ?? '';

        $res = VideoSubjectAction::index($this , $param);

        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }

        return success($res['message'] , $res['data']);
    }

    public function update($id)
    {
        $param = $this->request->post();
        $param['name']          = $param['name'] ?? '';
        $param['thumb']         = $param['thumb'] ?? '';
        $param['score']          = $param['score'] ?? '';
        $param['release_time']          = $param['release_time'] ?? null;
        $param['end_time']          = $param['end_time'] ?? null;
        $param['status']          = $param['status'] ?? '';
        $param['count']          = $param['count'] ?? '';
        $param['category_id']          = $param['category_id'] ?? '';
        $param['video_series_id']          = $param['video_series_id'] ?? '';
        $param['video_company_id']          = $param['video_company_id'] ?? '';
        $param['module_id']          = $param['module_id'] ?? '';
        $param['weight']          = $param['weight'] ?? '';
        $param['tags']          = $param['tags'] ?? '';

        $res = VideoSubjectAction::update($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function store()
    {
        $param = $this->request->post();
        $param['name']          = $param['name'] ?? '';
        $param['thumb']         = $param['thumb'] ?? '';
        $param['score']          = $param['score'] ?? '';
        $param['release_time']          = $param['release_time'] ?? null;
        $param['end_time']          = $param['end_time'] ?? null;
        $param['status']          = $param['status'] ?? '';
        $param['count']          = $param['count'] ?? '';
        $param['category_id']          = $param['category_id'] ?? '';
        $param['video_series_id']          = $param['video_series_id'] ?? '';
        $param['video_company_id']          = $param['video_company_id'] ?? '';
        $param['module_id']          = $param['module_id'] ?? '';
        $param['weight']          = $param['weight'] ?? '';
        $param['tags']          = $param['tags'] ?? '';

        $res = VideoSubjectAction::store($this ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function show($id)
    {
        $res = VideoSubjectAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroy($id)
    {
        $res = VideoSubjectAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = VideoSubjectAction::destroyAll($this , $ids);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroyTag()
    {
        $param = $this->request->post();
        $param['video_subject_id'] = $param['video_subject_id'] ?? '';
        $param['tag_id'] = $param['tag_id'] ?? '';
        $res = VideoSubjectAction::destroyTag($this , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function search()
    {
        $param = $this->request->query();
        $param['value'] = $param['value'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $param['module_id'] = $param['module_id'] ?? '';
        $res = VideoSubjectAction::search($this , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
