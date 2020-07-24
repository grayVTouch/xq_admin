<?php

namespace App\Customize\api\web_v1\mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = '用户注册';

    private $code = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $code)
    {
        //
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.register_email')
            ->with([
                'code' => $this->code
            ]);
    }
}
