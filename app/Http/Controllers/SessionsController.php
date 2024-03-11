<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        $user = User::query()
            ->where(['email' => $request->input('email'), 'is_deleted' => 0])
            ->first();
        if (!$user) {
            session()->flash('danger', '该邮箱不存在');
            return redirect()->back()->withInput();
        }
        if (Hash::check($user->password, $request->input('password'))) {
            session()->flash('danger', '您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }

        if (!$user->activated) {
            session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
            return redirect('/');
        }

        Auth::login($user);
        session()->flash('success', '欢迎回来！');
        $fallback = route('users.show', ['user' => Auth::user()]);
        return redirect()->intended($fallback);
    }

    public function destroy()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
