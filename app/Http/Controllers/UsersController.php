<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{
    public function __construct() {
        // 指定中间件
        $this->middleware('auth', ['except' => ['show']]);
    }

    /**
     * [show 显示页面]
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function show(User $user) { //此功能称为 『隐性路由模型绑定』，是『约定优于配置』设计范式的体现
        // compact() 函数创建一个包含变量名和它们的值的数组
        dd($user);
        return view('users.show', compact('user'));
    }

    /**
     * [edit 编辑页面]
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function edit(User $user) {
        //Controller 控制器基类包含了 Laravel 的 AuthorizesRequests trait。此 trait 提供了 authorize 方法
        $this->authorize('update', $user);// 验证授权
        return view('users.edit', compact('user'));
    }

    /**
     * [update 更新]
     * @param  Request $request [description]
     * @param  User    $user    [description]
     * @return [type]           [description]
     */
    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user) {
        // 验证授权
        $this->authorize('update', $user);
        $data = $request->all();

        if ( $request->avatar) {
                // 362 指定最大图片宽度(裁切)
                $result = $uploader->save($request->avatar, 'avatars', $user->id, 362);
                if ($result) {
                   $data['avatar'] = $result['path'];
                }

        }
        $user->update($data);

        return redirect()->route('users.show', $user->id)->with('success', '资料更新成功');
    }
}
