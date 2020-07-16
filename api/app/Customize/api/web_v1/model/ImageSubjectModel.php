<?php


namespace App\Customize\api\web_v1\model;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ImageSubjectModel extends Model
{
    protected $table = 'xq_image_subject';

    public static function getNewestByModuleIdAndLimit(int $module_id , int $limit = 0): Collection
    {
        return self::where([
                ['module_id' , '=' , $module_id] ,
                ['status' , '=' , 1] ,
            ])
            ->orderBy('create_time' , 'desc')
            ->orderBy('id' , 'asc')
            ->limit($limit)
            ->get();
    }

    public static function getHotByModuleIdAndLimit(int $module_id , int $limit = 0): Collection
    {
        return self::where([
                ['module_id' , '=' , $module_id] ,
                ['status' , '=' , 1] ,
            ])
            // 查看次数
            ->orderBy('view_count' , 'desc')
            // 点赞次数
            ->orderBy('praise_count' , 'desc')
            // todo 收藏次数
            // id 倒叙排序
            ->orderBy('create_time' , 'desc')
            ->orderBy('id' , 'asc')
            ->limit($limit)
            ->get();
    }

    public static function getHotWithPagerByModuleIdAndLimit(int $module_id , int $limit = 0): Paginator
    {
        return self::where([
            ['module_id' , '=' , $module_id] ,
            ['status' , '=' , 1] ,
        ])
            // 查看次数
            ->orderBy('view_count' , 'desc')
            // 点赞次数
            ->orderBy('praise_count' , 'desc')
            // todo 收藏次数
            // id 倒叙排序
            ->orderBy('create_time' , 'desc')
            ->orderBy('id' , 'asc')
            ->paginate($limit);
    }


    public static function getByModuleIdAndTagIdAndLimit(int $module_id , int $tag_id , int $limit = 0): Collection
    {
        return self::from('xq_image_subject as is')
            ->select('is.*')
            ->where([
                ['is.module_id' , '=' , $module_id] ,
                ['is.status' , '=' , 1] ,
            ])
            ->whereExists(function($query) use($tag_id){
                $query->select('id')
                    ->from('xq_relation_tag as rt')
                    ->where([
                        ['tag_id' , '=' , $tag_id] ,
                        ['relation_type' , '=' , 'image_subject'] ,
                    ])
                    ->whereRaw(DB::Raw('is.id = rt.relation_id'));
            })
            ->orderBy('is.create_time' , 'desc')
            ->orderBy('is.id' , 'asc')
            ->limit($limit)
            ->get();
    }

    // 标签对应的图片专题-非严格模式匹配
    public static function getByModuleIdAndTagIdsAndLimit(int $module_id , array $tag_ids = [] , int $limit = 0): Paginator
    {
        return self::from('xq_image_subject as is')
            ->select('is.*')
            ->where([
                ['is.module_id' , '=' , $module_id] ,
                ['is.status' , '=' , 1] ,
            ])
            ->whereExists(function($query) use($tag_ids){
                $query->select('id')
                    ->from('xq_relation_tag as rt')
                    ->where([
                        ['relation_type' , '=' , 'image_subject'] ,
                    ])
                    ->whereIn('tag_id' , $tag_ids)
                    ->whereRaw('is.id = rt.relation_id');
            })
            ->orderBy('is.create_time' , 'desc')
            ->orderBy('is.id' , 'asc')
            ->paginate($limit);
    }

    // 标签对应的图片专题-严格模式匹配
    public static function getInStrictByModuleIdAndTagIdsAndLimit(int $module_id , array $tag_ids = [] , int $limit = 0): Paginator
    {
        return self::from('xq_image_subject as is')
            ->select('is.*')
            ->where([
                ['is.module_id' , '=' , $module_id] ,
                ['is.status' , '=' , 1] ,
            ])
            ->whereExists(function($query) use($tag_ids){
                $query->select('id')
                    ->selectRaw('count(id) as total')
                    ->from('xq_relation_tag as rt')
                    ->where([
                        ['relation_type' , '=' , 'image_subject'] ,
                    ])
                    ->whereIn('tag_id' , $tag_ids)
                    ->whereRaw('is.id = rt.relation_id')
                    ->groupBy('relation_id')
                    ->having('total' , '=' , count($tag_ids));
            })
            ->orderBy('is.create_time' , 'desc')
            ->orderBy('is.id' , 'asc')
            ->paginate($limit);
    }

    public static function getNewestWithPagerByModuleIdAndLimit(int $module_id , int $limit = 0): Paginator
    {
        return self::where([
            ['module_id' , '=' , $module_id] ,
            ['status' , '=' , 1] ,
        ])
            ->orderBy('create_time' , 'desc')
            ->orderBy('id' , 'asc')
            ->paginate($limit);
    }

    public static function getWithPagerInStrictByModuleIdAndValueAndCategoryIdsAndSubjectIdsAndTagIdsAndOrderAndLimit(int $module_id , string $value = '' , array $category_ids = [] , array $subject_ids = [] , array $tag_ids = [] , $order = null , int $limit = 20)
    {
        $order = $order ?? ['field' => 'create_time' , 'value' => 'desc'];
        $value = strtolower($value);
        $where = [
            ['is.module_id' , '=' , $module_id] ,
        ];
        $query = self::from('xq_image_subject as is')
            ->where($where);
        if (!empty($category_ids)) {
            $query->whereIn('category_id' , $category_ids);
        }
        if (!empty($subject_ids)) {
            $query->where('is.type' , 'pro')
                ->whereIn('is.subject_id' , $subject_ids);
        }
        return $query->whereRaw('lower(is.name) like concat("%" , ? , "%")' , $value)
            ->whereExists(function($query) use($tag_ids){
                $query->select('id' , DB::raw('count(id) as total'))
                    ->from('xq_relation_tag')
                    ->where([
                        ['relation_type' , '=' , 'image_subject'] ,
                    ]);
                if (!empty($tag_ids)) {
                    $query->whereIn('tag_id' , $tag_ids)
                        ->groupBy('relation_id')
                        ->having('total' , '=' , count($tag_ids));
                }
                $query->whereRaw('relation_id = is.id');
            })
            ->orderBy($order['field'] , $order['value'])
            ->orderBy('id' , 'desc')
            ->paginate($limit);
    }

    public static function getWithPagerInLooseByModuleIdAndValueAndCategoryIdsAndSubjectIdsAndTagIdsAndOrderAndLimit(int $module_id , string $value = '' , array $category_ids = [] , array $subject_ids = [] , array $tag_ids = [] , $order = null , int $limit = 20)
    {
        $order = $order ?? ['field' => 'create_time' , 'value' => 'desc'];
        $value = strtolower($value);
        $where = [
            ['is.module_id' , '=' , $module_id] ,
        ];
        $query = self::from('xq_image_subject as is')
            ->where($where);
        if (!empty($category_ids)) {
            $query->whereIn('category_id' , $category_ids);
        }
        if (!empty($subject_ids)) {
            $query->where('is.type' , 'pro')
                ->whereIn('is.subject_id' , $subject_ids);
        }
        return $query->whereRaw('lower(is.name) like concat("%" , ? , "%")' , $value)
            ->whereExists(function($query) use($tag_ids){
                $query->select('id' , DB::raw('count(id) as total'))
                    ->from('xq_relation_tag')
                    ->where([
                        ['relation_type' , '=' , 'image_subject'] ,
                    ]);
                if (!empty($tag_ids)) {
                    $query->whereIn('tag_id' , $tag_ids);
                }
                $query->whereRaw('relation_id = is.id');
            })
            ->orderBy($order['field'] , $order['value'])
            ->orderBy('id' , 'desc')
            ->paginate($limit);
    }

    public static function countHandle(int $id , string $field , string $mode = '' , int $step = 1)
    {
        return self::where('id' , $id)->$mode($field , $step);
    }

    public static function recommendExcludeSelfByModuleIdAndCategoryIdAndSubjectIdAndLimit(int $image_subject_id , int $module_id , int $category_id , int $subject_id , int $limit = 20): Collection
    {
        $where = [
            ['id' , '!=' , $image_subject_id] ,
            ['module_id' , '=' , $module_id] ,
            ['category_id' , '=' , $category_id] ,
        ];
        if (empty($subject_id)) {
            $where[] = ['subject_id' , '=' , $subject_id];
        }
        return self::where($where)
            ->orderBy('view_count' , 'desc')
            ->orderBy('praise_count' , 'desc')
            ->orderBy('create_time' , 'desc')
            ->limit($limit)
            ->get();
    }
}
