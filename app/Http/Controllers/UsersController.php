<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{
    /**
     * [show 显示页面]
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function show(User $user) { //此功能称为 『隐性路由模型绑定』，是『约定优于配置』设计范式的体现
        // compact() 函数创建一个包含变量名和它们的值的数组
        return view('users.show', compact('user'));
    }

    /**
     * [edit 编辑页面]
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function edit(User $user) {
        return view('users.edit', compact('user'));
    }

    /**
     * [update 更新]
     * @param  Request $request [description]
     * @param  User    $user    [description]
     * @return [type]           [description]
     */
    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user) {
        $data = $request->all();

        if ( $request->avatar) {
                // 362 指定最大图片宽度
                $result = $uploader->save($request->avatar, 'avatars', $user->id, 362);
                if ($result) {
                   $data['avatar'] = $result['path'];
                }

        }
        $user->update($data);

        return redirect()->route('users.show', $user->id)->with('success', '资料更新成功');
    }
}
