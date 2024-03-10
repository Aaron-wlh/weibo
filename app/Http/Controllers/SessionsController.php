<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SessionsController extends Controller
{
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

        $user = User::query()->where('email', $request->input('email'))->first();
        if (!$user) {
            session()->flash('danger', '该邮箱不存在');
            return redirect()->back()->withInput();
        }
        if (Hash::check($user->password, $request->input('password'))) {
            session()->flash('danger', '您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }

        Auth::login($user);
        session()->flash('success', '欢迎回来！');
        return redirect()->route('users.show', ['user' => Auth::user()]);
    }

    public function destroy()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
