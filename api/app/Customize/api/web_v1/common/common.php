<?php

namespace api\web_v1;


use Illuminate\Contracts\Validation\Validator;
use Exception;
use Illuminate\Http\JsonResponse;
use stdClass;
use function extra\config;

/**
 * @param string $data
 * @param int $code
 * @return \Illuminate\Http\JsonResponse
 *@author running
 *
 */
function success($message = '' , $data = '' , $code = 200): JsonResponse
{
    return json($message , $data , $code);
}

/**
 * @author running
 *
 * @param string $data
 * @param int $code
 * @return \Illuminate\Http\JsonResponse
 */
function error($message = '' , $data = '' , $code = 400): JsonResponse
{
    return json($message , $data , $code);
}

/**
 * @author running
 *
 * @param $data
 * @param $code
 * @return \Illuminate\Http\JsonResponse
 */
function json($message , $data , $code): JsonResponse
{
    return response()->json([
        'message'  => $message ,
        'data' => $data
    ] , $code);
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
