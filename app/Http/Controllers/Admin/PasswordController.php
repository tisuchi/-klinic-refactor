<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function create()
    {
        return view('admin.pages.password.forget_password');
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|exists:users'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        User::where('email', $request->email)
            ->firstOrFail()
            ->update([
                'reset_token' => $token = Str::random(50),
                'reset_token_expire_at' => Carbon::now()->addDay(),
            ]);

        // I will suggest to trigger an event from here.
        Mail::to($request->email)->send(new ResetPasswordMail(route('reset.password', $token)));

        return redirect()->route('master.login');
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            'reset_token' => 'required',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::query()
            ->where("reset_token", $request->reset_token)
            ->isTokenAlived()
            ->firstOrFail();

        $user->update([
            'password' => bcrypt($request->password),
            'reset_token' => null
        ]);
    }

    public function show(string $token)
    {
        if (!$token) {
            abort(404);
        }

        return view('admin.pages.password.reset_password', compact('token'));
    }
}
