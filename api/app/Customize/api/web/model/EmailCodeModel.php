<?php


namespace App\Customize\api\web\model;


class EmailCodeModel extends Model
{
    protected $table = 'xq_email_code';

    public static function findByEmailAndType(string $email , string $type): ?EmailCodeModel
    {
        return self::where([
            ['email' , '=' , $email] ,
            ['type' , '=' , $type] ,
        ])->first();
    }
}
