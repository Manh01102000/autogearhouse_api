<?php

namespace App\Repositories\User;
// Interface
use App\Repositories\User\UserRepositoryInterface;
// Model
use App\Models\User;
use App\Models\Employees;
use App\Models\Customers;
use App\Models\Affiliate;
// Import Hash mã hóa 
use Illuminate\Support\Facades\Hash;
// Import db transaction
use Illuminate\Support\Facades\DB;
// JWT
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserRepository implements UserRepositoryInterface
{
    protected $model;
    protected $employees;
    protected $customers;
    protected $Affiliate;

    public function __construct(User $model, Employees $employees, Customers $customers, Affiliate $affiliate)
    {
        $this->model = $model;
        $this->employees = $employees;
        $this->customers = $customers;
        $this->affiliate = $affiliate;
    }

    public function searchUser(array $data)
    {
        $page = isset($data['page']) && is_numeric($data['page']) ? (int) $data['page'] : 1;
        $pageSize = isset($data['pageSize']) && is_numeric($data['pageSize']) ? (int) $data['pageSize'] : 20;

        $query = $this->model->query();

        // Nếu không có emp_role, lấy từ bảng users
        if (empty($data['emp_role']) && !empty($data['user_id'])) {
            $data['emp_role'] = $this->model->where('id', $data['user_id'])->value('user_role') ?? null;
        }

        // Lọc theo điều kiện nếu có
        if (!empty($data['emp_account'])) {
            $query->where('user_email_account', 'LIKE', "%{$data['emp_account']}%");
        }

        if (!empty($data['emp_role'])) {
            $query->where('user_role', $data['emp_role']);
        }

        if (!empty($data['emp_authentic'])) {
            $query->where('user_authentic', $data['emp_authentic']);
        }

        // Xác định quan hệ và cột phù hợp dựa trên emp_role
        $relations = [
            1 => ['table' => 'customers', 'foreign_key' => 'users.user_id', 'relation_key' => 'customers.customer_user_id'],
            2 => ['table' => 'employees', 'foreign_key' => 'users.user_id', 'relation_key' => 'employees.employee_user_id'],
            3 => ['table' => 'affiliate', 'foreign_key' => 'users.user_id', 'relation_key' => 'affiliate.affiliate_user_id'],
        ];

        $property = [
            1 => ['emp_name' => 'customer_name', 'emp_phone' => 'customer_phone', 'create_time' => 'customer_create_time'],
            2 => ['emp_name' => 'employee_name', 'emp_phone' => 'employee_phone', 'create_time' => 'employee_createAt'],
            3 => ['emp_name' => 'affiliate_name', 'emp_phone' => 'affiliate_phone', 'create_time' => 'affiliate_create_time'],
        ];

        if (!empty($data['emp_role']) && isset($relations[$data['emp_role']])) {
            $relation = $relations[$data['emp_role']];
            $properties = $property[$data['emp_role']] ?? [];

            $name_column = $properties['emp_name'] ?? null;
            $phone_column = $properties['emp_phone'] ?? null;
            $create_time_column = $properties['create_time'] ?? null;

            // Áp dụng JOIN thay vì whereHas
            $query->join($relation['table'], $relation['foreign_key'], '=', $relation['relation_key']);

            if ($name_column && !empty($data['emp_name'])) {
                $query->where($relation['table'] . '.' . $name_column, 'LIKE', "%{$data['emp_name']}%");
            }

            if ($phone_column && !empty($data['emp_phone'])) {
                $query->where($relation['table'] . '.' . $phone_column, 'LIKE', "%{$data['emp_phone']}%");
            }

            if ($create_time_column && !empty($data['emp_time_start']) && !empty($data['emp_time_end'])) {
                $start = date('Y-m-d 00:00:00', strtotime($data['emp_time_start']));
                $end = date('Y-m-d 23:59:59', strtotime($data['emp_time_end']));
                $query->whereBetween($relation['table'] . '.' . $create_time_column, [$start, $end]);
            }
        }

        if (!empty($data['sortBy'])) {
            $sortBy = $data['sortBy'];
            $sortOrder = !empty($data['sortOrder']) ? $data['sortOrder'] : 'desc';
            $query->orderBy('users.user_create_time', $sortOrder);
        }

        // Lấy danh sách user có phân trang
        $users = $query->paginate($pageSize, ['users.*', "{$relation['table']}.*"], 'page', $page);

        return [
            'success' => true,
            'message' => "Lấy dữ liệu thành công",
            'httpCode' => 200,
            'data' => ['users' => $users],
        ];
    }

    public function searchUserAdmin(array $data)
    {
        $page = isset($data['page']) && is_numeric($data['page']) ? (int) $data['page'] : 1;
        $pageSize = isset($data['pageSize']) && is_numeric($data['pageSize']) ? (int) $data['pageSize'] : 20;

        $query = $this->model->query()
            ->where('users.user_role', 2)
            ->join('employees', 'employees.employee_user_id', '=', 'users.user_id'); // Join bảng employees

        // Lọc theo employee_department (nếu có)
        if (!empty($data['emp_department'])) {
            $query->where('employees.employee_department', $data['emp_department']);
        }

        // Lọc các điều kiện khác
        if (!empty($data['emp_account'])) {
            $query->where('users.user_email_account', 'LIKE', '%' . trim($data['emp_account']) . '%');
        }

        if (!empty($data['emp_authentic'])) {
            $query->where('users.user_authentic', $data['emp_authentic']);
        }

        if (!empty($data['emp_name'])) {
            $query->where('employees.employee_name', 'LIKE', '%' . trim($data['emp_name']) . '%');
        }

        if (!empty($data['emp_phone'])) {
            $query->where('employees.employee_phone', 'LIKE', '%' . trim($data['emp_phone']) . '%');
        }

        if (!empty($data['emp_time_start']) && !empty($data['emp_time_end'])) {
            $start = date('Y-m-d 00:00:00', strtotime($data['emp_time_start']));
            $end = date('Y-m-d 23:59:59', strtotime($data['emp_time_end']));
            $query->whereBetween('employees.employee_createAt', [$start, $end]);
        }

        if (!empty($data['sortBy'])) {
            $sortBy = $data['sortBy'];
            $sortOrder = !empty($data['sortOrder']) ? $data['sortOrder'] : 'desc';
            $query->orderBy('users.user_create_time', $sortOrder);
        }

        // Phân trang dữ liệu
        $users = $query->paginate($pageSize, ['users.*', "employees.*"], 'page', $page);

        return [
            'success' => true,
            'message' => 'Lấy dữ liệu thành công',
            'httpCode' => 200,
            'data' => ['users' => $users],
        ];
    }

    public function findByID($id)
    {
        try {
            // Kiểm tra xem user có tồn tại không trước khi truy xuất user_role
            $user = $this->model->find($id);

            if (!$user) {
                return [
                    'success' => false,
                    'message' => "Không tìm thấy người dùng",
                    'httpCode' => 404,
                    'data' => [],
                ];
            }

            // Xác định quan hệ dựa trên user_role
            $relations = [
                1 => 'Customers',
                2 => 'Employees',
                3 => 'Affiliate',
            ];

            $relation = $relations[$user->user_role] ?? null;

            // Nếu có quan hệ hợp lệ thì load dữ liệu kèm theo
            if ($relation) {
                $user = $this->model->with($relation)->find($id);
            }

            return [
                'success' => true,
                'message' => "Lấy dữ liệu thành công",
                'httpCode' => 200,
                'data' => ['user' => $user],
            ];

        } catch (\Exception $e) {
            \Log::error("Lỗi khi lấy dữ liệu người dùng", [
                'error' => $e->getMessage(),
                'user_id' => $id,
            ]);

            return [
                'success' => false,
                'message' => "Lỗi server, vui lòng thử lại sau.",
                'httpCode' => 500,
                'data' => [],
            ];
        }
    }

    public function create(array $data)
    {
        try {
            $timestamp = time();

            $result = DB::transaction(function () use ($data, $timestamp) {
                // Kiểm tra xem email đã tồn tại chưa
                $existingUser = $this->model->where('user_email_account', $data['emp_account'])->first();
                if ($existingUser) {
                    return [
                        'success' => false,
                        'message' => "Email đã tồn tại, vui lòng sử dụng email khác.",
                        'httpCode' => 400,
                        'data' => [],
                    ];
                }

                $user = $this->model->create([
                    'user_role' => $data['emp_role'],
                    'user_email_account' => $data['emp_account'],
                    'password' => Hash::make($data['emp_password']),
                    'user_authentic' => 0,
                    'user_otp' => 0,
                    'user_create_time' => $timestamp,
                    'user_update_time' => $timestamp,
                    'last_login' => $timestamp,
                    'user_ip_address' => $data['ip_address'],
                ]);

                switch ($data['emp_role']) {

                    case 1: // Customer
                        $customerEmployeeId = $data['customer_employee_id'] ?? null;

                        if (!$customerEmployeeId) {
                            $employee = $this->employees::where('employee_show', '!=', 0)
                                ->where('employee_department', 2)
                                ->orderBy('last_assigned_at', 'asc')
                                ->first();

                            if (!$employee) {
                                return [
                                    'success' => false,
                                    'message' => "Không tìm thấy nhân viên kinh doanh phù hợp",
                                    'httpCode' => 400,
                                    'data' => [],
                                ];
                            }

                            $customerEmployeeId = $employee->employee_id;
                            $employee->update(['last_assigned_at' => $timestamp]);
                        }

                        $datacreate = [
                            'customer_user_id' => $user->user_id,
                            'customer_name' => $data['emp_name'],
                            'customer_email' => $data['emp_account'],
                            'customer_phone' => $data['emp_phone'],
                            'customer_birthday' => $data['emp_birth'],
                            'customer_show' => 0,
                            'customer_create_time' => $timestamp,
                            'customer_update_time' => $timestamp,
                            'customer_employee_id' => $customerEmployeeId,
                        ];

                        $this->customers->create($datacreate);
                        break;

                    case 2: // Employee
                        $datacreate = [
                            'employee_user_id' => $user->user_id,
                            'employee_name' => $data['emp_name'],
                            'employee_email' => $data['emp_account'],
                            'employee_phone' => $data['emp_phone'],
                            'employee_birthday' => $data['emp_birth'],
                            'employee_show' => 0,
                            'employee_createAt' => $timestamp,
                            'employee_updateAt' => $timestamp,
                        ];

                        $extraData = array_filter([
                            'employee_position' => $data['emp_position'] ?? null,
                            'employee_department' => $data['employee_department'] ?? null,
                            'employee_salary' => $data['employee_salary'] ?? null,
                        ]);

                        $datacreate = array_merge($datacreate, $extraData);
                        $this->employees->create($datacreate);
                        break;

                    default:
                        return [
                            'success' => false,
                            'message' => "Loại tài khoản không hợp lệ",
                            'httpCode' => 400,
                            'data' => [],
                        ];
                }

                return [
                    'success' => true,
                    'message' => "Đăng ký tài khoản thành công",
                    'httpCode' => 201,
                    'data' => ['user' => $user],
                ];
            });

            return $result;
        } catch (\Exception $e) {
            \Log::error("Lỗi khi đăng ký tài khoản", [
                'error' => $e->getMessage(),
                'data' => []
            ]);

            return [
                'success' => false,
                'message' => "Lỗi server, vui lòng thử lại sau.",
                'httpCode' => 500,
                'data' => [],
            ];
        }
    }

    public function login($account, $password)
    {
        try {
            $conditions = [
                'user_email_account' => $account,
                'password' => $password,
            ];

            /** === Dùng JWT để xác thực user === */
            if (!$token = JWTAuth::attempt($conditions)) {
                return apiResponse('error', 'Unauthorized', [], false, 401);
            }

            /** === Lấy dữ liệu người dùng từ === */
            $user = Auth::user();

            /** === Lấy dữ liệu chi tiết của người dùng === */
            $user_name = '';
            if ($user->user_role == '2') {
                $user = User::with('Employees')->find($user->user_id);
                $user_name = $user->employees['0']->employee_name;
            } elseif ($user->user_role == '1') {
                $user = User::with('Customers')->find($user->user_id);
                $user_name = $user->customers['0']->customer_name;
            }

            /** === Trả kết quả === */
            return [
                'success' => true,
                'message' => "Đăng nhập tài khoản thành công",
                'httpCode' => 201,
                'data' => [
                    'user' => [
                        'user_id' => $user->user_id,
                        'user_role' => $user->user_role,
                        'user_authentic' => $user->user_authentic,
                        'user_create_time' => $user->user_create_time,
                        'user_name' => $user_name,
                    ],
                    'token' => $token,
                ]
            ];
        } catch (\Exception $e) {
            \Log::error("Lỗi khi đăng ký tài khoản", [
                'error' => $e->getMessage(),
                'data' => []
            ]);

            return [
                'success' => false,
                'message' => "Lỗi server, vui lòng thử lại sau.",
                'httpCode' => 500,
                'data' => [],
            ];
        }
    }

    public function update($id, array $data)
    {
        try {

            $timestamp = time();
            return DB::transaction(function () use ($id, $data, $timestamp) {
                // Tìm user cần cập nhật
                $user = $this->model->find($id);

                if (!$user) {
                    return [
                        'success' => false,
                        'message' => "Không tìm thấy tài khoản",
                        'httpCode' => 404,
                        'data' => [],
                    ];
                }

                // Mảng dữ liệu cần cập nhật
                $userData = [
                    'user_authentic' => $data['emp_authentic'] ?? $user->user_authentic,
                    'user_update_time' => $timestamp,
                    'user_ip_address' => $data['ip_address'] ?? $user->user_ip_address,
                ];

                // Cập nhật mật khẩu nếu có
                if (!empty($data['emp_password'])) {
                    $userData['password'] = Hash::make($data['emp_password']);
                }

                // Cập nhật thông tin user
                $user->update($userData);

                // Xử lý cập nhật theo `emp_role`
                switch ($user->user_role) {
                    case 1: // Customer
                        $customer = $this->customers->where('customer_user_id', $id)->first();
                        if ($customer) {
                            $customer->update([
                                'customer_name' => $data['emp_name'] ?? $customer->customer_name,
                                'customer_phone' => $data['emp_phone'] ?? $customer->customer_phone,
                                'customer_birthday' => $data['emp_birth'] ?? $customer->customer_birthday,
                                'customer_update_time' => $timestamp,
                            ]);
                        }
                        break;

                    case 2: // Employee
                        $employee = $this->employees->where('employee_user_id', $id)->first();
                        if ($employee) {
                            $employee->update([
                                'employee_name' => $data['emp_name'] ?? $employee->employee_name,
                                'employee_phone' => $data['emp_phone'] ?? $employee->employee_phone,
                                'employee_birthday' => $data['emp_birth'] ?? $employee->employee_birthday,
                                'employee_updateAt' => $timestamp,
                                'employee_position' => $data['emp_position'] ?? $employee->employee_position,
                                'employee_department' => $data['emp_department'] ?? $employee->employee_department,
                                'employee_salary' => $data['emp_salary'] ?? $employee->employee_salary,
                            ]);
                        }
                        break;

                    default:
                        // Nếu `user_role` không hợp lệ
                        return [
                            'success' => false,
                            'message' => "Loại tài khoản không hợp lệ",
                            'httpCode' => 400,
                            'data' => [],
                        ];
                }

                return [
                    'success' => true,
                    'message' => "Cập nhật tài khoản thành công",
                    'httpCode' => 200,
                    'data' => ['user' => $user],
                ];
            });
        } catch (\Exception $e) {
            \Log::error("Lỗi khi cập nhật tài khoản", [
                'error' => $e->getMessage(),
                'user_id' => $id,
                'data' => []
            ]);

            return [
                'success' => false,
                'message' => "Lỗi server, vui lòng thử lại sau.",
                'httpCode' => 500,
                'data' => [],
            ];
        }
    }
    public function delete($id)
    {
        try {
            // Lấy thông tin user
            $user = $this->model->find($id);

            if (!$user) {
                return [
                    'success' => false,
                    'message' => "Không tìm thấy người dùng",
                    'httpCode' => 404,
                    'data' => [],
                ];
            }

            // Xóa user trong transaction để đảm bảo tính toàn vẹn
            DB::transaction(function () use ($user) {
                $user->delete();
            });

            return [
                'success' => true,
                'message' => "Xóa dữ liệu thành công",
                'httpCode' => 200,
                'data' => ['user_id' => $id],
            ];

        } catch (\Exception $e) {
            \Log::error("Lỗi khi xóa người dùng", [
                'error' => $e->getMessage(),
                'user_id' => $id,
            ]);

            return [
                'success' => false,
                'message' => "Lỗi server, vui lòng thử lại sau.",
                'httpCode' => 500,
                'data' => [],
            ];
        }
    }

    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }
}
