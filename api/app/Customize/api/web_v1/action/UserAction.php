<?php


namespace App\Customize\api\web_v1\action;

use App\Customize\api\web_v1\handler\CollectionGroupHandler;
use App\Customize\api\web_v1\handler\CollectionHandler;
use App\Customize\api\web_v1\handler\HistoryHandler;
use App\Customize\api\web_v1\handler\ImageSubjectHandler;
use App\Customize\api\web_v1\handler\UserHandler;
use App\Customize\api\web_v1\model\CollectionGroupModel;
use App\Customize\api\web_v1\model\CollectionModel;
use App\Customize\api\web_v1\model\HistoryModel;
use App\Customize\api\web_v1\model\ImageSubjectModel;
use App\Customize\api\web_v1\model\ModuleModel;
use App\Customize\api\web_v1\model\PraiseModel;
use App\Customize\api\web_v1\model\UserModel;
use App\Customize\api\web_v1\model\UserTokenModel;
use App\Customize\api\web_v1\util\CollectionGroupUtil;
use App\Http\Controllers\api\web_v1\Base;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mews\Captcha\Facades\Captcha;
use function api\web_v1\get_form_error;
use function api\web_v1\my_config;
use function api\web_v1\user;
use function core\array_unit;
use function core\current_time;
use function core\random;

class UserAction extends Action
{

    public static function destroyCollectionGroup(Base $context , array $param = []): array
    {
        $param['collection_group_ids'] = [$param['collection_group_id']];
        return self::destroyAllCollectionGroup($context , $param);
    }

    public static function destroyAllCollectionGroup(Base $context , array $param = []): array
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'collection_group_ids' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        $collection_group_ids = empty($param['collection_group_ids']) ? [] : json_decode($param['collection_group_ids'] , true);
        if (empty($collection_group_ids)) {
            return self::error('请提供待删除的收藏夹');
        }
        $user = user();
        try {
            DB::beginTransaction();
            CollectionGroupModel::delByModuleIdAndUserIdAndIds($module->id , $user->id , $collection_group_ids);
            CollectionModel::delByModuleIdAndUserIdAndCollectionGroupIds($module->id , $user->id , $collection_group_ids);
            DB::commit();
            return self::success();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function destroyCollection(Base $context , array $param = []): array
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'collection_id' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        $user = user();
        $res = CollectionModel::delByModuleIdAndUserIdAndId($module->id , $user->id , $param['collection_id']);
        return self::success($res);
    }

    public static function store(Base $context , array $param = []): array
    {
        $validator = Validator::make($param , [
            'username' => 'required|min:6' ,
            'password' => 'required|min:6' ,
            'confirm_password' => 'required|min:6' ,
            'captcha_code' => 'required|min:4' ,
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        if (empty($param['captcha_key'])) {
            return self::error('必要参数丢失【captcha_key】');
        }
        if (!Captcha::check_api($param['captcha_code'] , $param['captcha_key'])) {
            return self::error([
                'captcha_code' => '图形验证码错误',
            ]);
        }
        $user = UserModel::findByUsername($param['username']);
        if (!empty($user)) {
            return self::error([
                'username' => '用户已经存在'
            ]);
        }
        if ($param['password'] !== $param['confirm_password']) {
            return self::error([
                'password' => '两次输入的密码不一致' ,
            ]);
        }
        $token = random(32 , 'mixed' , true);
        $datetime = date('Y-m-d H:i:s' , time() + 7 * 24 * 3600);
        try {
            DB::beginTransaction();
            $id = UserModel::insertGetId([
                'username' => $param['username'] ,
                'password' => Hash::make($param['password']) ,
                'last_time' => date('Y-m-d H:i:s'),
                'last_ip'   => $context->request->ip(),
            ]);
            UserTokenModel::insertGetId([
                'user_id' => $id ,
                'token' => $token ,
                'expired' => $datetime
            ]);
            DB::commit();
            return self::success($token);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function login(Base $context , array $param = []): array
    {
        $validator = Validator::make($param , [
            'username' => 'required|min:6' ,
            'password' => 'required|min:6' ,
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $user = UserModel::findByUsername($param['username']);
        if (empty($user)) {
            return self::error([
                'username' => '用户不存在' ,
            ]);
        }
        if (!Hash::check($param['password'] , $user->password)) {
            return self::error([
                'password' => '密码错误' ,
            ]);
        }
        $token = random(32 , 'mixed' , true);
        $datetime = date('Y-m-d H:i:s' , time() + 7 * 24 * 3600);
        try {
            DB::beginTransaction();
            UserTokenModel::insert([
                'user_id' => $user->id ,
                'token' => $token ,
                'expired' => $datetime
            ]);
            UserModel::updateById($user->id , [
                'last_time' => date('Y-m-d H:i:s'),
                'last_ip'   => $context->request->ip(),
            ]);
            DB::commit();
            return self::success($token);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function info(Base $context , array $param = [])
    {
        $user = user();
        if (empty($user)) {
            return self::error('用户尚未登录' , 401);
        }
        return self::success($user);
    }

    public static function updatePassword(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'username' => 'required|min:6' ,
            'password' => 'required|min:6' ,
            'confirm_password' => 'required|min:6' ,
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $user = UserModel::findByUsername($param['username']);
        if (empty($user)) {
            return self::error([
                'username' => '用户不存在' ,
            ]);
        }
        if ($param['password'] !== $param['confirm_password']) {
            return self::error([
                'password' => '两次输入的密码不一致' ,
            ]);
        }
        UserModel::updateById($user->id , [
            'password' => Hash::make($param['password'])
        ]);
        return self::success();
    }

    public static function lessHistory(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id'             => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        $user = user();
        $limit = $param['limit'] ? $param['limit'] : my_config('app.limit');
        $res = HistoryModel::getOrderTimeByModuleIdAndUserIdAndLimit($module->id , $user->id , $limit);
        $res = HistoryHandler::handleAll($res);
        $date = date('Y-m-d');
        $yesterday = date_create('yesterday')->format('Y-m-d');
        $groups = [];
        $findIndex = function($name) use(&$groups): int
        {
            foreach ($groups as $k => $v)
            {
                if ($v['name'] === $name) {
                    return $k;
                }
            }
            return -1;
        };
        foreach ($res as $v)
        {
            switch ($v->date)
            {
                case $date:
                    $name = '今天';
                    break;
                case $yesterday:
                    $name = '昨天';
                    break;
                default:
                    $name = $v->date;
            }
            $index = $findIndex($name);
            if ($index < 0) {
                $groups[] = [
                    'name' => $name ,
                    'data' => [] ,
                ];
                $index = count($groups) - 1;
            }
            $groups[$index]['data'][] = $v;
        }
        return self::success($groups);
    }

    public static function histories(Base $context , array $param = []): array
    {
        $relation_type_range = array_keys(my_config('business.relation_type_for_history'));
        $validator = Validator::make($param , [
            'module_id'             => 'required|integer' ,
            'relation_type' => ['sometimes' , Rule::in($relation_type_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        $user = user();
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = HistoryModel::getByModuleIdAndUserIdAndRelationTypeAndValueAndLimit($module->id , $user->id , $param['relation_type'] , $param['value'] ,$limit);
        $res = HistoryHandler::handlePaginator($res);
        // 对时间进行分组
        $date = date('Y-m-d');
        $yesterday = date_create('yesterday')->format('Y-m-d');
        $groups = [];
        $findIndex = function($name) use(&$groups): int
        {
            foreach ($groups as $k => $v)
            {
                if ($v['name'] === $name) {
                    return $k;
                }
            }
            return -1;
        };
        foreach ($res->data as $v)
        {
            switch ($v->date)
            {
                case $date:
                    $name = '今天';
                    break;
                case $yesterday:
                    $name = '昨天';
                    break;
                default:
                    $name = $v->date;
            }
            $index = $findIndex($name);
            if ($index < 0) {
                $groups[] = [
                    'name' => $name ,
                    'data' => [] ,
                ];
                $index = count($groups) - 1;
            }
            $groups[$index]['data'][] = $v;
        }
        $res->data = $groups;
        return self::success($res);
    }


    public static function collectionGroupWithJudge(Base $context , array $param = []): array
    {
        $relation_type_range = array_keys(my_config('business.relation_type_for_collection'));
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'relation_type' => ['required' , Rule::in($relation_type_range)] ,
            'relation_id' => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        switch ($param['relation_type'])
        {
            case 'image_subject':
                $relation = ImageSubjectModel::find($param['relation_id']);
                break;
            default:
                $relation = null;
        }
        if (empty($relation)) {
            return self::error('关联事物不存在');
        }
        $user = user();
        $res = CollectionGroupModel::getByModuleIdAndUserIdAndValue($module->id , $user->id);
        $res = CollectionGroupHandler::handleAll($res);
        foreach ($res as $v)
        {
            CollectionGroupUtil::handle($v , $param['relation_type'] , $relation->id);
        }
        return self::success($res);
    }

    public static function collectionGroup(Base $context , array $param = []): array
    {
        $relation_type_range = array_keys(my_config('business.relation_type_for_collection'));
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'relation_type' => ['sometimes' , Rule::in($relation_type_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        $user = user();
        $res = CollectionGroupModel::getByModuleIdAndUserIdAndRelationTypeAndValue($module->id , $user->id , $param['relation_type'] ,  $param['value']);
        $res = CollectionGroupHandler::handleAll($res);
        return self::success($res);
    }

    public static function collectionHandle(Base $context , array $param = [])
    {
        $relation_type_range = array_keys(my_config('business.relation_type_for_collection'));
        $action_range = array_keys(my_config('business.bool_for_int'));
        $validator = Validator::make($param , [
            'module_id'             => 'required|integer' ,
            'collection_group_id'   => 'required|integer' ,
            'action'                => ['required' , Rule::in($action_range)] ,
            'relation_type' => ['required' , Rule::in($relation_type_range)] ,
            'relation_id' => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        $collection_group = CollectionGroupModel::find($param['collection_group_id']);
        if (empty($collection_group)) {
            return self::error('收藏夹不存在' , 404);
        }
        $user = user();
        $res = null;
        if ($param['relation_type'] === 'image_subject') {
            $relation = ImageSubjectModel::find($param['relation_id']);
            if (empty($relation)) {
                return self::error('关联事物不存在');
            }
            if ($param['action'] == 1) {
                // 收藏
                CollectionModel::insertOrIgnore([
                    'module_id' => $module->id ,
                    'user_id' => $user->id ,
                    'collection_group_id' => $collection_group->id ,
                    'relation_type' => $param['relation_type'] ,
                    'relation_id' => $relation->id ,
                    'create_time' => date('Y-m-d H:i:s')
                ]);
            } else {
                // 取消收藏
                CollectionModel::delByModuleIdAndUserIdAndCollectionGroupIdAndRelationTypeAndRelationId($module->id , $user->id , $collection_group->id , 'image_subject' , $relation->id);
            }
        } else {
            // 其他类型，预留
        }
        $collection_group = CollectionGroupHandler::handle($collection_group);
        CollectionGroupUtil::handle($collection_group , $param['relation_type'] , $relation->id);
        return self::success($collection_group);
    }

    //
    public static function praiseHandle(Base $context , array $param = [])
    {
        $relation_type_range = array_keys(my_config('business.relation_type_for_praise'));
        $action_range = array_keys(my_config('business.bool_for_int'));
        $validator = Validator::make($param , [
            'module_id'             => 'required|integer' ,
            'action'                => ['required' , Rule::in($action_range)] ,
            'relation_type' => ['required' , Rule::in($relation_type_range)] ,
            'relation_id' => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        if ($param['relation_type'] === 'image_subject') {
            // 图片专题
            $relation = ImageSubjectModel::find($param['relation_id']);
            if (empty($relation)) {
                return self::error('图片专题不存在');
            }
            $user = user();
            if ($param['action'] == 1) {
                // 点赞
                PraiseModel::insertOrIgnore([
                    'module_id' => $module->id ,
                    'user_id' => $user->id ,
                    'relation_type' => $param['relation_type'] ,
                    'relation_id' => $relation->id ,
                    'create_time' => date('Y-m-d H:i:s')
                ]);
                ImageSubjectModel::countHandle($relation->id , 'praise_count' , 'increment');
            } else {
                // 取消收藏
                PraiseModel::delByModuleIdAndUserIdAndRelationTypeAndRelationId($module->id , $user->id , 'image_subject' , $relation->id);
                ImageSubjectModel::countHandle($relation->id , 'praise_count' , 'decrement');
            }
            $res = ImageSubjectModel::find($relation->id);
            $res = ImageSubjectHandler::handle($res);
        } else {
            // 其他类型，预留
        }
        return self::success($res);
    }

    public static function record(Base $context , array $param = [])
    {
        $relation_type_range = array_keys(my_config('business.relation_type_for_history'));
        $validator = Validator::make($param , [
            'module_id'             => 'required|integer' ,
            'relation_type' => ['required' , Rule::in($relation_type_range)] ,
            'relation_id' => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        switch ($param['relation_type'])
        {
            case 'image_subject':
                $relation = ImageSubjectModel::find($param['relation_id']);
                break;
        }
        if (empty($relation)) {
            return self::error('关联事物不存在' , 404);
        }
        $user = user();
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $res = HistoryModel::updateOrInsert([
            'module_id' => $module->id ,
            'user_id' => $user->id ,
            'relation_type' => $param['relation_type'] ,
            'relation_id' => $relation->id ,
            'date' => date('Y-m-d') ,
        ] , [
            'time' => date('H:i:s') ,
            'create_time' => $date . ' ' . $time ,
        ]);
        return self::success($res);
    }

    public static function createAndJoinCollectionGroup(Base $context , array $param = [])
    {
        $relation_type_range = array_keys(my_config('business.relation_type_for_collection'));
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'relation_type' => ['required' , Rule::in($relation_type_range)] ,
            'relation_id' => 'required|integer' ,
            'name'      => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        switch ($param['relation_type'])
        {
            case 'image_subject':
                $relation = ImageSubjectModel::find($param['relation_id']);
                break;
            default:
                $relation = null;
        }
        if (empty($relation)) {
            return self::error('关联的事物不存在' , 404);
        }
        $user = user();
        $collection_group = CollectionGroupModel::findByModuleIdAndUserIdAndName($module->id , $user->id , $param['name']);
        if (!empty($collection_group)) {
            return self::error('收藏夹已经存在');
        }
        try {
            DB::beginTransaction();
            $id = CollectionGroupModel::insertGetId([
                'module_id' => $module->id ,
                'user_id' => $user->id ,
                'name' => $param['name'] ,
                'create_time' => current_time() ,
            ]);
            CollectionModel::insertGetId([
                'module_id' => $module->id ,
                'user_id' => $user->id ,
                'collection_group_id' => $id ,
                'relation_type' => $param['relation_type'] ,
                'relation_id' => $relation->id ,
                'create_time' => current_time() ,
            ]);
            $collection_group = CollectionGroupModel::find($id);
            $collection_group = CollectionGroupHandler::handle($collection_group);
            CollectionGroupUtil::handle($collection_group , $param['relation_type'] , $relation->id);
            DB::commit();
            return self::success($collection_group);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function createCollectionGroup(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'name'      => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        $user = user();
        $collection_group = CollectionGroupModel::findByModuleIdAndUserIdAndName($module->id , $user->id , $param['name']);
        if (!empty($collection_group)) {
            return self::error('收藏夹已经存在');
        }
        $res = CollectionGroupModel::insertGetId([
            'module_id' => $module->id ,
            'user_id' => $user->id ,
            'name' => $param['name'] ,
            'create_time' => current_time() ,
        ]);
        return self::success($res);
    }

    public static function joinCollectionGroup(Base $context , array $param = [])
    {
        $relation_type_range = array_keys(my_config('business.relation_type_for_collection'));
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'relation_type' => ['required' , Rule::in($relation_type_range)] ,
            'relation_id' => 'required|integer' ,
            'collection_group_id' => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        switch ($param['relation_type'])
        {
            case 'image_subject':
                $relation = ImageSubjectModel::find($param['relation_id']);
                break;
            default:
                $relation = null;
        }
        if (empty($relation)) {
            return self::error('关联的事物不存在' , 404);
        }
        $user = user();
        $collection_group = CollectionGroupModel::find($param['collection_group_id']);
        if (empty($collection_group)) {
            return self::error('收藏夹不存在' , 404);
        }
        CollectionModel::insertGetId([
            'module_id' => $module->id ,
            'user_id' => $user->id ,
            'collection_group_id' => $collection_group->id ,
            'relation_type' => $param['relation_type'] ,
            'relation_id' => $relation->id ,
            'create_time' => current_time() ,
        ]);
        $collection_group = CollectionGroupHandler::handle($collection_group);
        CollectionGroupUtil::handle($collection_group , $param['relation_type'] , $relation->id);
        return self::success($collection_group);
    }

    public static function lessRelationInCollection(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'collection_group_id' => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        $collection_group = CollectionGroupModel::find($param['collection_group_id']);
        if (empty($collection_group)) {
            return self::error('收藏夹不存在' , 404);
        }
        $limit = $param['limit'] ? $param['limit'] : my_config('app.limit');
        $user = user();
        $res = CollectionModel::getByModuleIdAndUserIdAndCollectionGroupIdAndLimit($module->id , $user->id , $collection_group->id , $limit);
        $res = CollectionHandler::handleAll($res);
        return self::success($res);
    }

    public static function lessCollectionGroupWithCollection(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        $collection_limit = $param['collection_limit'] ? $param['collection_limit'] : my_config('app.limit');
        $relation_limit = $param['relation_limit'] ? $param['relation_limit'] : my_config('app.limit');
        $user = user();
        $total_collection_group = CollectionGroupModel::countByModuleIdAndUserId($module->id , $user->id);
        $collection_group = CollectionGroupModel::getByModuleIdAndUserIdAndLimit($module->id , $user->id , $collection_limit);
        $collection_group = CollectionGroupHandler::handleAll($collection_group);
        foreach ($collection_group as $v)
        {
            $collections = CollectionModel::getByModuleIdAndUserIdAndCollectionGroupIdAndLimit($module->id , $user->id , $v->id , $relation_limit);
            $collections = CollectionHandler::handleAll($collections);
            $v->collections = $collections;
        }
        return self::success([
            'total_collection_group' => $total_collection_group ,
            'collection_groups' => $collection_group ,
        ]);
    }

    public static function update(Base $context , array $param = [])
    {
        $sex_range = array_keys(my_config('business.sex_for_user'));
        $validator = Validator::make($param , [
            'sex' => ['required' , Rule::in($sex_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $user = user();
        $param['birthday'] = empty($param['birthday']) ? $user->birthday : $param['birthday'];
        UserModel::updateById($user->id , array_unit($param , [
            'nickname' ,
            'sex' ,
            'avatar' ,
            'phone' ,
            'email' ,
            'description' ,
            'birthday' ,
        ]));
        $user = UserModel::find($user->id);
        $user = UserHandler::handle($user);
        return self::success($user);
    }


    public static function updatePasswordInLogged(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'old_password' => 'required' ,
            'password'      => 'required|min:6' ,
            'confirm_password' => 'required|min:6' ,
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $user = user();
        if (!Hash::check($param['old_password'] , $user->password)) {
            return self::error([
                'old_password' => '原密码错误'
            ]);
        }
        if ($param['password'] !== $param['confirm_password']) {
            return self::error('两次输入的密码不一致');
        }
        $password = Hash::make($param['password']);
        UserModel::updateById($user->id , [
            'password' => $password
        ]);
        return self::success();
    }

    public static function destroyHistory(Base $context , array $param = []): array
    {
        $validator = Validator::make($param , [
            'module_id' => 'required' ,
            'history_ids'      => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        $history_ids = empty($param['history_ids']) ? [] : json_decode($param['history_ids'] , true);
        if (empty($history_ids)) {
            return self::error('请提供待删除的项');
        }
        $user = user();
        $histories = HistoryModel::getByModuleIdAndUserIdAndIds($module->id , $user->id , $history_ids);
        if (count($history_ids) !== count($histories)) {
            return self::error('存在无效记录，请重新选择');
        }
        // 检查记录是否是当前登录用户
        $count = HistoryModel::destroy($history_ids);
        return self::success($count);
    }

    public static function collections(Base $context , array $param = []): array
    {
        $relation_type_range = array_keys(my_config('business.relation_type_for_collection'));
        $validator = Validator::make($param , [
            'module_id' => 'required' ,
            'collection_group_id' => 'required' ,
            'relation_type' => ['sometimes' , Rule::in($relation_type_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        $collection_group = CollectionGroupModel::find($param['collection_group_id']);
        if (empty($collection_group)) {
            return self::error('收藏夹不存在' , 404);
        }
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $user = user();
        $res = CollectionModel::getWithPagerByModuleIdAndUserIdAndCollectionGroupIdAndLimit($module->id , $user->id , $collection_group->id , $param['relation_type'] , $limit);
        $res = CollectionHandler::handlePaginator($res);
        return self::success($res);
    }

    public static function updateCollectionGroup(Base $context , array $param = []): array
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'collection_group_id' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        $collection_group = CollectionGroupModel::find($param['collection_group_id']);
        if (empty($collection_group)) {
            return self::error('收藏夹不存在');
        }
        $user = user();
        if (!empty(CollectionGroupModel::findByModuleIdAndUserIdAndNameExcludeIds($module->id , $user->id , $param['name'] , [$collection_group->id]))) {
            return self::error('名称已经被使用');
        }
        CollectionGroupModel::updateById($collection_group->id , [
            'name' => $param['name']
        ]);
        $res = CollectionGroupModel::find($collection_group->id);
        $res = CollectionGroupHandler::handle($res);
        return self::success($res);
    }

}