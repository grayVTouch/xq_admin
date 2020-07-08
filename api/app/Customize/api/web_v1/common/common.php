<?php

namespace api\web_v1;


use App\Customize\api\web_v1\model\UserModel;
use Illuminate\Contracts\Validation\Validator;
use Exception;
use stdClass;
use function extra\config;

/**
 * @param string $data
 * @param int $code
 * @return \Illuminate\Http\JsonResponse
 *@author running
 *
 */
function success($data = '' , $code = 200)
{
    return json($data , $code);
}

/**
 * @author running
 *
 * @param string $data
 * @param int $code
 * @return \Illuminate\Http\JsonResponse
 */
function error($data = '' , $code = 400)
{
    return json($data , $code);
}

/**
 * @author running
 *
 * @param $data
 * @param $code
 * @return \Illuminate\Http\JsonResponse
 */
function json($data , $code)
{
    if (is_scalar($data) || is_null($data)) {
        return response($data , $code);
    }
    return response()->json($data , $code);
}


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

function user(): ?stdClass
{
    try {
        return resolve('user');
    } catch (Exception $e) {
        return null;
    }
}

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

function my_config($key , $value = [])
{
    $dir = __DIR__ . '/../config';
    return config($dir , $key , $value);
}

function get_value($key , $value)
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
