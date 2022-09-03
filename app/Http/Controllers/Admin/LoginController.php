<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('admin.pages.login.login');
    }

    public function store(Request $request)
    {
        $userlogin = $request->except('_token');

        if (Auth::attempt($userlogin)) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back();
    }

    public function destroy()
    {
        if (!Auth::check()) {
            abort(404);
        }

        Auth::logout();

        return redirect()->route('home')->with('message', 'Logged out.');
    }
}
