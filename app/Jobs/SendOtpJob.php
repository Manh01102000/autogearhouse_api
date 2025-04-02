<?php
// 🔹 1. Khai báo Namespace & Import Class
namespace App\Jobs;

use App\Mail\OtpMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

// implements ShouldQueue → Cho Laravel biết job này sẽ chạy trên queue.
class SendOtpJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $name;
    public $email;
    public $subject;
    public $otp;
    /** Tạo một job mới. */
    public function __construct($name, $email, $subject, $otp)
    {
        $this->name = $name;
        $this->email = $email;
        $this->subject = $subject;
        $this->otp = $otp;
    }

    /** Xử lý job trong queue. */
    public function handle()
    {
        // Tạo một instance của OtpMail và sử dụng Mail::to()->send() để gửi OTP.
        $email = new OtpMail($this->name, $this->subject, $this->otp);
        Mail::to($this->email)->send($email);
    }
}