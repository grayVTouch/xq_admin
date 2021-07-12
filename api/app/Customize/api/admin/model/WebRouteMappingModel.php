<?php


namespace App\Customize\api\admin\model;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

class WebRouteMappingModel extends Model
{
    protected $table = 'xq_web_route_mapping';

    public static function findBySymbol(string $symbol): ?WebRouteMappingModel
    {
        return self::where('symbol' , $symbol)->first();
    }
}
