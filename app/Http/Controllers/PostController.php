<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function update(Request $request, Post $post)
    {
        if (Gate::denies('update', $post)) {
            return response()->json(['message' => 'Bạn không có quyền chỉnh sửa bài viết này!'], 403);
        }

        $post->update($request->all());
        return response()->json(['message' => 'Cập nhật thành công!']);
    }
}
