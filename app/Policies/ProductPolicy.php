<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Employees;
class ProductPolicy
{
    public function create(User $user)
    {
        // Kiểm tra nếu user có role == 2
        if ($user->user_role !== 2) {
            return false;
        }

        // Kiểm tra bảng employee xem department có phải 1 không
        $employee = Employees::where('user_id', $user->id)->first();
        if (!$employee || $employee->department !== 1) {
            return false;
        }

        return true;
    }

}
