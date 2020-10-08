<?php


namespace App\Customize\api\web\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ImageProjectModel extends Model
{
    protected $table = 'xq_image_project';

    public static function getNewestByFilterAndLimit(array $filter = [] , int $limit = 0): Collection
    {
        $filter['module_id'] = $filter['module_id'] ?? '';
        $filter['type']      = $filter['type'] ?? '';

        $where = [
            ['status' , '=' , 1] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['module_id' , '=' , $filter['module_id']];
        }

        if ($filter['type'] !== '') {
            $where[] = ['type' , '=' , $filter['type']];
        }

        return self::where($where)
            ->orderBy('created_at' , 'desc')
            ->orderBy('id' , 'asc')
            ->limit($limit)
            ->get();
    }

    public static function getHotByFilterAndLimit(array $filter = [] , int $limit = 0): Collection
    {
        $filter['module_id'] = $filter['module_id'] ?? '';
        $filter['type']      = $filter['type'] ?? '';

        $where = [
            ['status' , '=' , 1] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['module_id' , '=' , $filter['module_id']];
        }

        if ($filter['type'] !== '') {
            $where[] = ['type' , '=' , $filter['type']];
        }
        return self::where($where)
            // 查看次数
            ->orderBy('view_count' , 'desc')
            // 点赞次数
            ->orderBy('praise_count' , 'desc')
            // todo 收藏次数
            // id 倒叙排序
            ->orderBy('created_at' , 'desc')
            ->orderBy('id' , 'asc')
            ->limit($limit)
            ->get();
    }

    public static function getHotWithPagerByFilterAndLimit(array $filter = [] , int $limit = 0): Paginator
    {
        $filter['module_id'] = $filter['module_id'] ?? '';
        $filter['type']      = $filter['type'] ?? '';

        $where = [
            ['status' , '=' , 1] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['module_id' , '=' , $filter['module_id']];
        }

        if ($filter['type'] !== '') {
            $where[] = ['type' , '=' , $filter['type']];
        }
        return self::where($where)
            // 查看次数
            ->orderBy('view_count' , 'desc')
            // 点赞次数
            ->orderBy('praise_count' , 'desc')
            // todo 收藏次数
            // id 倒叙排序
            ->orderBy('created_at' , 'desc')
            ->orderBy('id' , 'asc')
            ->paginate($limit);
    }


    public static function getByTagIdAndFilterAndLimit(int $tag_id , array $filter = [] , int $limit = 0): Collection
    {
        $filter['module_id'] = $filter['module_id'] ?? '';
        $filter['type']      = $filter['type'] ?? '';

        $where = [
            ['is.status' , '=' , 1] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['is.module_id' , '=' , $filter['module_id']];
        }

        if ($filter['type'] !== '') {
            $where[] = ['is.type' , '=' , $filter['type']];
        }
        return self::from('xq_image_subject as is')
            ->select('is.*')
            ->where($where)
            ->whereExists(function($query) use($tag_id){
                $query->select('id')
                    ->from('xq_relation_tag as rt')
                    ->where([
                        ['tag_id' , '=' , $tag_id] ,
                        ['relation_type' , '=' , 'image_project'] ,
                    ])
                    ->whereRaw(DB::Raw('is.id = rt.relation_id'));
            })
            ->orderBy('is.created_at' , 'desc')
            ->orderBy('is.id' , 'asc')
            ->limit($limit)
            ->get();
    }

    // 标签对应的图片专题-非严格模式匹配
    public static function getByTagIdsAndFilterAndLimit(array $tag_ids = [] , array $filter = [] , int $limit = 0): Paginator
    {
        $filter['module_id'] = $filter['module_id'] ?? '';
        $filter['type']      = $filter['type'] ?? '';

        $where = [
            ['is.status' , '=' , 1] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['is.module_id' , '=' , $filter['module_id']];
        }

        if ($filter['type'] !== '') {
            $where[] = ['is.type' , '=' , $filter['type']];
        }

        return self::from('xq_image_subject as is')
            ->select('is.*')
            ->where($where)
            ->whereExists(function($query) use($tag_ids){
                $query->select('id')
                    ->from('xq_relation_tag as rt')
                    ->where([
                        ['relation_type' , '=' , 'image_project'] ,
                    ])
                    ->whereIn('tag_id' , $tag_ids)
                    ->whereRaw('is.id = rt.relation_id');
            })
            ->orderBy('is.created_at' , 'desc')
            ->orderBy('is.id' , 'asc')
            ->paginate($limit);
    }

    // 标签对应的图片专题-严格模式匹配
    public static function getInStrictByTagIdsAndFilterAndLimit(array $tag_ids = [] , array $filter = [] , int $limit = 0): Paginator
    {
        $filter['module_id'] = $filter['module_id'] ?? '';
        $filter['type']      = $filter['type'] ?? '';

        $where = [
            ['is.status' , '=' , 1] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['is.module_id' , '=' , $filter['module_id']];
        }

        if ($filter['type'] !== '') {
            $where[] = ['is.type' , '=' , $filter['type']];
        }

        return self::from('xq_image_subject as is')
            ->select('is.*')
            ->where($where)
            ->whereExists(function($query) use($tag_ids){
                $query->select('id')
                    ->selectRaw('count(id) as total')
                    ->from('xq_relation_tag as rt')
                    ->where([
                        ['relation_type' , '=' , 'image_project'] ,
                    ])
                    ->whereIn('tag_id' , $tag_ids)
                    ->whereRaw('is.id = rt.relation_id')
                    ->groupBy('relation_id')
                    ->having('total' , '=' , count($tag_ids));
            })
            ->orderBy('is.created_at' , 'desc')
            ->orderBy('is.id' , 'asc')
            ->paginate($limit);
    }

    public static function getNewestWithPagerByFilterAndLimit(array $filter = [] , int $limit = 0): Paginator
    {
        $filter['module_id'] = $filter['module_id'] ?? '';
        $filter['type']      = $filter['type'] ?? '';

        $where = [
            ['status' , '=' , 1] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['module_id' , '=' , $filter['module_id']];
        }

        if ($filter['type'] !== '') {
            $where[] = ['type' , '=' , $filter['type']];
        }

        return self::where($where)
            ->orderBy('created_at' , 'desc')
            ->orderBy('id' , 'asc')
            ->paginate($limit);
    }

    public static function getWithPagerInStrictByFilterAndOrderAndLimit(array $filter = [] , $order = null , int $limit = 20)
    {
        $filter['value']        = $filter['value'] ?? '';
        $filter['module_id']    = $filter['module_id'] ?? '';
        $filter['type']         = $filter['type'] ?? '';
        $filter['category_ids'] = $filter['category_ids'] ?? [];
        $filter['subject_ids']  = $filter['subject_ids'] ?? [];
        $filter['tag_ids']      = $filter['tag_ids'] ?? [];

        $order = $order ?? ['field' => 'created_at' , 'value' => 'desc'];
        $value = strtolower($filter['value']);

        $where = [
            ['is.status' , '=' , 1] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['is.module_id' , '=' , $filter['module_id']];
        }

        if ($filter['type'] !== '') {
            $where[] = ['is.type' , '=' , $filter['type']];
        }

        $query = self::from('xq_image_subject as is')
            ->where($where);

        if (!empty($filter['category_ids'])) {
            $query->whereIn('category_id' , $filter['category_ids']);
        }

        if (!empty($filter['subject_ids'])) {
            $query->whereIn('is.subject_id' , $filter['subject_ids']);
        }

        return $query->whereRaw("lower(is.name) like ?" , "%{$value}%")
            ->whereExists(function($query) use($filter){
                $query->select('id' , DB::raw('count(id) as total'))
                    ->from('xq_relation_tag')
                    ->where([
                        ['relation_type' , '=' , 'image_project'] ,
                    ]);
                if (!empty($filter['tag_ids'])) {
                    $query->whereIn('tag_id' , $filter['tag_ids'])
                        ->groupBy('relation_id')
                        ->having('total' , '=' , count($filter['tag_ids']));
                }
                $query->whereRaw('relation_id = is.id');
            })
            ->orderBy("is.{$order['field']}" , $order['value'])
            ->orderBy('is.id' , 'desc')
            ->paginate($limit);
    }

    public static function getWithPagerInLooseByFilterAndOrderAndLimit(array $filter = [] , $order = null , int $limit = 20)
    {
        $filter['value']        = $filter['value'] ?? '';
        $filter['module_id']    = $filter['module_id'] ?? '';
        $filter['type']         = $filter['type'] ?? '';
        $filter['category_ids'] = $filter['category_ids'] ?? [];
        $filter['subject_ids']  = $filter['subject_ids'] ?? [];
        $filter['tag_ids']      = $filter['tag_ids'] ?? [];

        $order = $order ?? ['field' => 'created_at' , 'value' => 'desc'];
        $value = strtolower($filter['value']);

        $where = [
            ['is.status' , '=' , 1] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['is.module_id' , '=' , $filter['module_id']];
        }

        if ($filter['type'] !== '') {
            $where[] = ['is.type' , '=' , $filter['type']];
        }

        $query = self::from('xq_image_subject as is')
            ->where($where);

        if (!empty($filter['category_ids'])) {
            $query->whereIn('category_id' , $filter['category_ids']);
        }

        if (!empty($filter['subject_ids'])) {
            $query->whereIn('is.subject_id' , $filter['subject_ids']);
        }

        return $query->whereRaw("lower(is.name) like ?" , "%{$value}%")
            ->whereExists(function($query) use($filter){
                $query->select('id' , DB::raw('count(id) as total'))
                    ->from('xq_relation_tag')
                    ->where([
                        ['relation_type' , '=' , 'image_project'] ,
                    ]);

                if (!empty($filter['tag_ids'])) {
                    $query->whereIn('tag_id' , $filter['tag_ids']);
                }

                $query->whereRaw('relation_id = is.id');
            })
            ->orderBy("is.{$order['field']}" , $order['value'])
            ->orderBy('is.id' , 'desc')
            ->paginate($limit);
    }

    public static function countHandle(int $id , string $field , string $mode = '' , int $step = 1)
    {
        return self::where('id' , $id)->$mode($field , $step);
    }

    public static function recommendExcludeSelfByFilterAndLimit(int $self_id , array $filter = [] , int $limit = 20): Collection
    {
        $filter['module_id']    = $filter['module_id'] ?? '';
        $filter['category_id']  = $filter['category_id'] ?? '';
        $filter['image_subject_id']   = $filter['image_subject_id'] ?? '';
        $filter['type']         = $filter['type'] ?? '';

        $where = [
            ['id' , '!=' , $self_id] ,
        ];

        if ($filter['module_id'] !== '') {
            $where[] = ['module_id' , '=' , $filter['module_id']];
        }

        if ($filter['category_id'] !== '') {
            $where[] = ['category_id' , '=' , $filter['category_id']];
        }

        if ($filter['image_subject_id'] !== '') {
            $where[] = ['image_subject_id' , '=' , $filter['image_subject_id']];
        }

        if ($filter['type'] !== '') {
            $where[] = ['type' , '=' , $filter['type']];
        }

        return self::where($where)
            ->orderBy('view_count' , 'desc')
            ->orderBy('praise_count' , 'desc')
            ->orderBy('created_at' , 'desc')
            ->limit($limit)
            ->get();
    }
}
