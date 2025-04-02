<?php

namespace App\Http\Controllers;

use App\Jobs\SendOtpJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// Model User
use App\Models\User;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'email' => 'required|email',
            ]);

            // Tạo OTP ngẫu nhiên
            $otp = rand(100000, 999999);
            $expireTime = time();

            // Lưu OTP vào database
            $user = User::where('user_email_account', $request->email)->first();
            if (!$user) {
                return response()->json(["message" => "Không tìm thấy người dùng"], 404);
            }

            $user->update([
                'user_otp' => $otp,
                'user_otp_expired' => $expireTime,
            ]);

            // Gửi OTP qua queue
            dispatch(new SendOtpJob($request->name, $request->email, "Mã OTP của bạn", $otp));
            Log::info("OTP sent to: " . $request->email . " | OTP: " . $otp);

            return apiResponse("success", "OTP đã được gửi thành công!", ['email' => $request->email], true, 200);
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => "Lỗi hệ thống: " . $e->getMessage(),
            ], 500);
        }
    }
}
