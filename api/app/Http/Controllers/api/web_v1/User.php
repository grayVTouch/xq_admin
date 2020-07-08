<?php


namespace App\Http\Controllers\api\web_v1;


use App\Customize\api\web_v1\action\ImageSubjectWithAuthAction;
use App\Customize\api\web_v1\action\LoginAction;
use App\Customize\api\web_v1\action\UserAction;
use function api\web_v1\error;
use function api\web_v1\success;

class User extends Base
{

    // 删除收藏夹
    public function destroyCollectionGroup($collection_group_id)
    {
        $res = UserAction::destroyCollectionGroup($this , $collection_group_id);
        if ($res['code'] !== 0) {
            return error($res['data'], $res['code']);
        }
        return success($res['data']);
    }

    // 删除收藏夹
    public function destroyAllCollectionGroup()
    {
        $collection_group_ids = $this->request->post('collection_group_ids' , []);
        $res = UserAction::destroyAllCollectionGroup($this , $collection_group_ids);
        if ($res['code'] !== 0) {
            return error($res['data'], $res['code']);
        }
        return success($res['data']);
    }

    public function store()
    {
        $param = $this->request->post();
        $param['username']      = $param['username'] ?? '';
        $param['password']      = $param['password'] ?? '';
        $param['confirm_password']      = $param['confirm_password'] ?? '';
        $param['captcha_key']    = $param['captcha_key'] ?? '';
        $param['captcha_code']   = $param['captcha_code'] ?? '';
        $res = UserAction::store($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function info()
    {
        $res = UserAction::info($this);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function login()
    {
        $param = $this->request->post();
        $param['username']      = $param['username'] ?? '';
        $param['password']      = $param['password'] ?? '';
        $res = UserAction::login($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    // 更改密码
    public function updatePassword()
    {
        $param = $this->request->post();
        $param['username']      = $param['username'] ?? '';
        $param['password']      = $param['password'] ?? '';
        $param['confirm_password']      = $param['confirm_password'] ?? '';
        $res = UserAction::updatePassword($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function historyLimit()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = UserAction::historyLimit($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function histories()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['relation_type'] = $param['relation_type'] ?? '';
        $param['value'] = $param['value'] ?? '';
        $res = UserAction::histories($this , '' , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function collectionGroup()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['relation_type'] = $param['relation_type'] ?? '';
        $param['relation_id'] = $param['relation_id'] ?? '';
        $res = UserAction::collectionGroup($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    // 收藏 & 取消收藏
    public function collectionHandle()
    {
        $param = $this->request->post();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['relation_type']    = $param['relation_type'] ?? '';
        $param['relation_id']    = $param['relation_id'] ?? '';
        $param['action']    = $param['action'] ?? '';
        $param['collection_group_id'] = $param['collection_group_id'] ?? '';
        $res = UserAction::collectionHandle($this , $param);
        if ($res['code'] !== 0) {
            return error($res['data'], $res['code']);
        }
        return success($res['data']);
    }

    // 点赞 & 取消点赞
    public function praiseHandle()
    {
        $param = $this->request->post();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['relation_type']    = $param['relation_type'] ?? '';
        $param['relation_id']    = $param['relation_id'] ?? '';
        $param['action']    = $param['action'] ?? '';
        $res = UserAction::praiseHandle($this , $param);
        if ($res['code'] !== 0) {
            return error($res['data'], $res['code']);
        }
        return success($res['data']);
    }

    public function record()
    {
        $param = $this->request->post();
        $param['module_id']      = $param['module_id'] ?? '';
        $param['relation_type']    = $param['relation_type'] ?? '';
        $param['relation_id']    = $param['relation_id'] ?? '';
        $res = UserAction::record($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    // 创建并加入收藏夹
    public function createAndJoinCollectionGroup()
    {
        $param = $this->request->post();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['name'] = $param['name'] ?? '';
        $param['relation_type']    = $param['relation_type'] ?? '';
        $param['relation_id']    = $param['relation_id'] ?? '';
        $res = UserAction::createAndJoinCollectionGroup($this , $param);
        if ($res['code'] !== 0) {
            return error($res['data'], $res['code']);
        }
        return success($res['data']);
    }

    // 仅加入收藏夹
    public function joinCollectionGroup()
    {
        $param = $this->request->post();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['collection_group_id'] = $param['collection_group_id'] ?? '';
        $param['relation_type']    = $param['relation_type'] ?? '';
        $param['relation_id']    = $param['relation_id'] ?? '';
        $res = UserAction::joinCollectionGroup($this , $param);
        if ($res['code'] !== 0) {
            return error($res['data'], $res['code']);
        }
        return success($res['data']);
    }
}
