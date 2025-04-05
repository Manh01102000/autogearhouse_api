<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Validate
use Illuminate\Validation\ValidationException;
// Repository
use App\Repositories\User\UserRepositoryInterface;
// Import Hash mã hóa 
use Illuminate\Support\Facades\Hash;
// JWT
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
// model
use App\Models\User;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        try {
            $ip_address = client_ip();

            $request->validate([
                'emp_account' => 'required|string|email|max:255',
                'emp_name' => 'required|string|max:255',
                'emp_password' => 'required|string|min:6',
                'emp_phone' => 'required|string|max:255',
                'emp_birth' => 'required|string|max:255',
            ]);

            /** === Lấy dữ liệu từ repository === **/
            $response = $this->userRepository->create([
                // Tài khoản
                'emp_account' => $request->emp_account,
                // Tên
                'emp_name' => $request->emp_name,
                // Mật khẩu
                'emp_password' => $request->emp_password,
                // Số điện thoại
                'emp_phone' => $request->emp_phone,
                // Ngày sinh
                'emp_birth' => $request->emp_birth,
                // địa chỉ IP
                'ip_address' => $ip_address ?? '',
                // Quyền 1:khách hàng, 2: nhân viên
                'emp_role' => $request->emp_role ?? 1,
                // Dành cho đăng ký nhân viên
                // Vị trí
                'emp_position' => $request->emp_position ?? "",
                // Phòng ban
                'emp_department' => $request->emp_department ?? "",
                // Mức lương
                'emp_salary' => $request->emp_salary ?? "",
            ]);

            if ($response['success']) {
                $user = $response['data']['user'];

                /** === Tạo token bằng JWT === **/
                $token = JWTAuth::fromUser($user);

                $data_mess = [
                    'data' => $user,
                    'token' => $token,
                ];

                return apiResponse("success", $response['message'], $data_mess, true, $response['httpCode']);
            } else {
                return apiResponse('error', $response['message'], $response['data'], false, $response['httpCode']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => "Lỗi hệ thống: " . $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            /** === Kiểm tra dữ liệu đầu vào === */
            $request->validate([
                'account' => 'required|string|email',
                'password' => 'required|string|min:6',
            ]);
            $response = $this->userRepository->login($request->account, $request->password);

            if ($response['success']) {
                return apiResponse("success", $response['message'], $response['data'], true, $response['httpCode']);
            } else {
                return apiResponse('error', $response['message'], $response['data'], false, $response['httpCode']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => "Lỗi hệ thống: " . $e->getMessage(),
            ], 500);
        }
    }


    // Làm mới token
    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh());
    }

    public function checkToken(Request $request)
    {
        try {
            // Kiểm tra xem token có tồn tại trong header không
            if (!$token = $request->header('Authorization')) {
                return apiResponse("error", "Token is invalid", [], false, 401);
            }
            $token = str_replace('Bearer ', '', $token);
            // Xác thực token
            JWTAuth::setToken($token)->authenticate();
            // Trả về thông tin user hoặc quyền hạn của user
            return apiResponse("success", "Token is valid", auth()->user(), true, 200);
        } catch (TokenInvalidException $e) {
            return apiResponse("error", "Token is invalid", [], false, 401);
        } catch (TokenExpiredException $e) {
            return apiResponse("error", "Token has expired", [], false, 401);
        } catch (\Exception $e) {
            return apiResponse("error", "Authorization token not found", [], false, 401);
        }
    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60 // Dùng JWTAuth thay vì auth()
        ]);
    }

    // Đăng xuất
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logged out successfully']);
    }
}
