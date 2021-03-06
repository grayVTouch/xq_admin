<?php


namespace App\Customize\api\web\action;


use App\Customize\api\web\mail\PasswordEmail;
use App\Customize\api\web\mail\RegisterEmail;
use App\Customize\api\web\model\EmailCodeModel;
use App\Http\Controllers\api\web\Base;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Mews\Captcha\Facades\Captcha;
use function api\web\get_form_error;
use function api\web\my_config;
use function api\web\my_config_keys;
use function core\current_datetime;
use function core\random;

class MiscAction extends Action
{
    public static function captcha(Base $context , array $param = [])
    {
        $res = Captcha::create('default' , true);
        return self::success('' , $res);
    }

    public static function sendEmailCode(Base $context , string $type ,  array $param = [])
    {
        $validator = Validator::make($param , [
            'email' => 'required|email'
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , $validator->errors());
        }
        $type_range = my_config_keys('business.email_code_type');
        if (!in_array($type , $type_range)) {
            return self::error('不支持的验证码类型，当前支持的类型有：' . implode(',' , $type_range));
        }
        $timestamp = current_datetime();
        $code = random(4 , 'number' , true);
        switch ($type)
        {
            case 'password':
                $mailable = new PasswordEmail($code);
                break;
            case 'register':
                $mailable = new RegisterEmail($code);
                break;
            default:
                throw new Exception('不支持的类型');
        }
        Mail::to($param['email'])
            ->send($mailable);

        $email_code = EmailCodeModel::findByEmailAndType($param['email'] , $type);
        if (empty($email_code)) {
            // 不存在插入
            EmailCodeModel::insertOrIgnore([
                'email' => $param['email'] ,
                'type' => $type ,
                'is_used' => 0 ,
                'code' => $code ,
                'send_at' => $timestamp ,
                'updated_at' => $timestamp ,
                'created_at' => $timestamp ,
            ]);
        } else {
            // 存在则更新
            EmailCodeModel::updateById($email_code->id , [
                'email' => $param['email'] ,
                'type' => $type ,
                'is_used' => 0 ,
                'code' => $code ,
                'send_at' => $timestamp ,
                'updated_at' => $timestamp ,
            ]);
        }
        return self::success('操作成功');
    }
}
