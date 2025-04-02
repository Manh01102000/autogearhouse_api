<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    //Khởi tạo thuộc tính (property)
    public $use_name;
    public $subject;
    public $otp;
    //Khỏi tạo hàm construct
    public function __construct($use_name, $subject, $otp)
    {
        $this->use_name = $use_name;
        $this->subject = $subject;
        $this->otp = $otp;
    }

    // Khỏi tạo hàm build
    public function build()
    {
        return $this->from('fashionhouses3725@gmail.com', 'Fashion Houses')
            ->subject($this->subject)
            ->view('emails.custom_email') // Truyền dữ liệu vào Blade
            ->with([
                'name' => $this->use_name,
                'CodeOTP' => $this->otp,
                'nameweb' => "Fashion Houses",
                'title' => $this->subject
            ]);
    }

}
