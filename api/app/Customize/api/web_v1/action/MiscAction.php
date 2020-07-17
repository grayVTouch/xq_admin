<?php


namespace App\Customize\api\web_v1\action;


use App\Customize\api\web_v1\model\EmailCodeModel;
use App\Http\Controllers\api\web_v1\Base;
use App\Mail\web_v1\PasswordEmail;
use App\Mail\web_v1\RegisterEmail;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Mews\Captcha\Facades\Captcha;
use function api\web_v1\get_form_error;
use function api\web_v1\my_config;
use function core\current_time;
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
            return self::error('表单错误，请检查' , get_form_error($validator));
        }
        $type_range = array_keys(my_config('business.email_code_type'));
        if (!in_array($type , $type_range)) {
            return self::error('不支持的验证码类型，当前支持的类型有：' . implode(',' , '' , $type_range));
        }
        $timestamp = current_time();
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
                'used' => 0 ,
                'code' => $code ,
                'send_time' => $timestamp ,
                'update_time' => $timestamp ,
                'create_time' => $timestamp ,
            ]);
        } else {
            // 存在则更新
            EmailCodeModel::updateById($email_code->id , [
                'email' => $param['email'] ,
                'type' => $type ,
                'used' => 0 ,
                'code' => $code ,
                'send_time' => $timestamp ,
                'update_time' => $timestamp ,
            ]);
        }
        return self::success();
    }
}
