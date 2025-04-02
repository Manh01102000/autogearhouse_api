<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\User\UserRepositoryInterface;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Cập nhật thông tin user
     */
    public function updateUser(Request $request, $id)
    {
        try {
            if (empty($request->all())) {
                return apiResponse("error", "Dữ liệu gửi lên trống", [], false, 400);
            }

            $ip_address = client_ip();
            $data = [
                'id' => $id,
                'emp_name' => $request->input('emp_name'), // Kiểm tra có tồn tại không
                'emp_phone' => $request->input('emp_phone'),
                'emp_birth' => $request->input('emp_birth'),
                'ip_address' => $ip_address ?? '',
                'emp_role' => $request->input('emp_role', null), // Dùng giá trị mặc định
                'emp_position' => $request->input('emp_position', null),
                'emp_department' => $request->input('emp_department', null),
                'emp_salary' => $request->input('emp_salary', null),
            ];
            
            // Chỉ lọc giá trị null, giữ lại chuỗi rỗng nếu cần
            $updateData = array_filter($data, function ($value) {
                return !is_null($value);
            });

            if (empty($updateData)) {
                return apiResponse("error", "Không có dữ liệu hợp lệ để cập nhật", [], false, 400);
            }

            $response = $this->userRepository->update($id, $updateData);
            if ($response['success']) {
                return apiResponse("success", $response['message'], $response['data'], true, $response['httpCode']);
            } else {
                return apiResponse('error', $response['message'], $response['data'], false, $response['httpCode']);
            }
        } catch (\Exception $e) {
            logger()->error('Lỗi cập nhật user: ' . $e->getMessage());
            return apiResponse("error", "Lỗi hệ thống: " . $e->getMessage(), [], false, 500);
        }
    }

    // Lấy dữ liệu người dùng có kèm tìm kiếm
    public function searchUser(Request $request)
    {
        try {
            $filters = $request->all();
            // Lấy danh sách tất cả user với phân trang 10 user mỗi trang
            $response = $this->userRepository->searchUser($filters);
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

    public function searchUserAdmin(Request $request)
    {
        try {
            $filters = $request->all();
            // Lấy danh sách tất cả user với phân trang 10 user mỗi trang
            $response = $this->userRepository->searchUserAdmin($filters);
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

    /**
     * Lấy user theo ID
     */
    public function getUserById($id)
    {
        try {
            $response = $this->userRepository->findByID($id);
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

    /**
     * Xóa user
     */
    public function deleteUser($id)
    {
        try {
            $response = $this->userRepository->delete($id);
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
}
