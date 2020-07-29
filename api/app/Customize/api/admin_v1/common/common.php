<?php

namespace api\admin_v1;


use Illuminate\Contracts\Validation\Validator;
use Exception;
use Illuminate\Support\Facades\Storage;
use function core\format_path;
use function extra\config;
/**
 * @param string $data
 * @param int $code
 * @return \Illuminate\Http\JsonResponse
 *@author running
 *
 */
function success($message = '' , $data = '' , $code = 200)
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
function error($message = '' , $data = '' , $code = 400)
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
function json($message , $data , $code)
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

function user()
{
    return app()->make('user');
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

// 获取资源路径前缀
function res_path_prefix(): string
{
    $path = Storage::disk()->getAdapter()->getPathPrefix();
    return format_path($path);
}

// 获取资源的真实路径（仅适用于本地文件系统保存的资源）
function res_realpath(string $relative_path = ''): string
{
    if (empty($relative_path)) {
        return '';
    }
    return format_path(Storage::path($relative_path));
}
