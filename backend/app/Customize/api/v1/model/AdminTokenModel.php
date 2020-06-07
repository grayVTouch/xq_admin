<?php


namespace App\Customize\api\v1\model;


use function core\convert_obj;

class AdminTokenModel extends Model
{
    protected $table = 'xq_admin_token';

    public function user()
    {
        return $this->belongsTo(AdminModel::class , 'user_id' , 'id');
    }

    public static function findByToken(string $token = '')
    {
        $res = self::where('token' , $token)->first();
        if (empty($res)) {
            return ;
        }
        $res = convert_obj($res);
        return $res;
    }
}
