<?php

namespace api\admin;


use Illuminate\Contracts\Validation\Validator;
use Exception;
use stdClass;
use function extra\config;

/**
 * 成功响应
 *
 * @author running
 *
 * @param string $data
 * @param int $code
 * @return \Illuminate\Http\JsonResponse
 *
 */
function success($message = '' , $data = '' , $code = 200)
{
    return json($message , $data , $code);
}

/**
 * 失败响应
 *
 * @author running
 *
 * @param string $data
 * @param int $code
 * @return \Illuminate\Http\JsonResponse
 */
function error($message = '' , $data = '' , $code = 400)
{
    return json($message , $data , $code);
}

/**
 *
 * json 响应
 *
 * @author running
 *
 * @param $data
 * @param $code
 * @return \Illuminate\Http\JsonResponse
 */
function json($message , $data , $code)
{
    return response()->json([
        'message'  => $message ,
        'data' => $data
    ] , $code);
}

/**
 * 表单错误
 *
 * @author running
 *
 * @param Validator $validator
 * @return array
 */
function get_form_error(Validator $validator)
{
    $res = [];
    $message_bags = $validator->errors()->toArray();
    foreach ($message_bags as $k => $v)
    {
        $res[$k] = $v[0];
    }
    return $res;
}

/**
 * 用户信息获取
 *
 * @return mixed
 * @throws \Illuminate\Contracts\Container\BindingResolutionException
 */
function user(): ?stdClass
{
    try {
        return app()->make('user');
    } catch (Exception $e) {
        return null;
    }
}

/**
 * 获取排序字段
 *
 * @author running
 *
 * @param string $order
 * @param string $delimiter
 * @return array
 * @throws Exception
 */
function parse_order(string $order = '' , $delimiter = '|')
{
    if (empty($order)) {
        throw new Exception('参数 1 错误');
    }
    $res = explode($delimiter , $order);
    return [
        'field' => $res[0] ,
        'value' => $res[1] ,
    ];
}

/**
 * 获取配置值
 *
 * @author running
 *
 * @param string $key
 * @param array $value
 * @return mixed|string
 * @throws Exception
 */
function my_config(string $key , array $value = [])
{
    $dir = __DIR__ . '/../config';
    return config($dir , $key , $value);
}


/**
 * 获取配置项 key 列表
 *
 * @author running
 *
 * @param string $key
 * @return array
 * @throws Exception
 */
function my_config_keys(string $key)
{
    return array_keys(my_config($key));
}

/**
 * 获取配置项 key 映射的 value
 *
 * @author running
 *
 * @param string $key
 * @param $value
 * @return mixed|string
 * @throws Exception
 */
function get_config_key_mapping_value(string $key , $value)
{
    $range = my_config($key);
    foreach ($range as $k => $v)
    {
        if ($k == $value) {
            return $v;
        }
    }
    return '';
}
