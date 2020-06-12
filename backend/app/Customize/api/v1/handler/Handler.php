<?php


namespace App\Customize\api\v1\handler;


use Illuminate\Contracts\Pagination\Paginator;
use stdClass;
use function core\convert_obj;

class Handler
{
    /**
     * @param mixed $list 可迭代的结构
     * @return array
     */
    public static function handleAll($list) :array
    {
        $res = [];
        foreach ($list as $v)
        {
            $res[] = static::handle($v);
        }
        return $res;
    }

    public static function handlePaginator(Paginator $paginator): stdClass
    {
        $data = static::handleAll($paginator->items());
        $obj = convert_obj($paginator);
        $obj->data = $data;
        return $obj;
    }
}
