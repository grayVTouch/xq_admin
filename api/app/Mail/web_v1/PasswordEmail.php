<?php

namespace App\Mail\web_v1;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $code = '';

    public $subject = '忘记密码';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.password_email')
            ->with([
                'code' => $this->code
            ]);
    }
}
