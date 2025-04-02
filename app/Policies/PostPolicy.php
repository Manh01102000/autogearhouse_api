<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**  Chỉ cho phép user chỉnh sửa bài viết của họ. */
    public function update(User $user, Post $post)
    {
        return $user->id === $post->user_id;
    }

    /** Chỉ cho phép user xóa bài viết của họ. */
    public function delete(User $user, Post $post)
    {
        return $user->id === $post->user_id;
    }
}
