<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->paginate(10);

        return view('admin.pages.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required',
            'email' => 'email|required',
            'password' => 'required',
            'role_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $image = '';

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image = date('Ymdhms') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/uploads/userimage', $image);
        }

        User::create([
            'username' => $request->name,
            'role_id' => $request->role_id,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'image' => $image
        ]);

        return redirect()->route('user.list')->with('success', 'User created successfully.');;
    }

    public function create()
    {
        $roles = Role::all();

        return view('admin.pages.users.add', compact('roles'));
    }

    public function show(string $userId)
    {
        $users = User::findOrFail($userId);

        return view('admin.pages.users.view', compact('users'));
    }
}
