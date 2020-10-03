<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\Model;
use Illuminate\Contracts\Pagination\Paginator;
use stdClass;
use function core\convert_object;

class Handler
{
    /**
     * 处理可迭代结构
     *
     * @author running
     *
     * @param mixed $list  可迭代结构
     * @return array
     */
    public static function handleAll($list , array $with = []) :array
    {
        $res = [];
        foreach ($list as $v)
        {
            $res[] = static::handle($v , $with);
        }
        return $res;
    }

    /**
     * 处理分页
     *
     * @author running
     *
     * @param Paginator $paginator
     * @return stdClass
     */
    public static function handlePaginator(Paginator $paginator , array $with = []): stdClass
    {
        $data = static::handleAll($paginator->items() , $with);
        $obj = convert_object($paginator);
        $obj->data = $data;
        return $obj;
    }

//    public static function handle(?Model $model , array $with = []): ?stdClass
    public static function handle(?Model $model , array $with = []): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        return convert_object($model);
    }

    public static function resolve(string $key , array $with = [] , callable $callback = null)
    {
        $keys = array_keys($with);
        if (!in_array($key , $with) && !in_array($key , $keys)) {
            // 不符合返回的条件
            return ;
        }
        if (in_array($key , $keys)) {
            // 要求返回
            if (is_callable($callback)) {
                // 回调函数处理
                call_user_func($callback , $key , $with[$key]);
            }
            return ;
        }
    }
}
