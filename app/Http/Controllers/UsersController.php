<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function show(User $user) { //此功能称为 『隐性路由模型绑定』，是『约定优于配置』设计范式的体现
        // compact() 函数创建一个包含变量名和它们的值的数组
        return view('users.show', compact('user'));
    }
}
