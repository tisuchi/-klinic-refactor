<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Staff;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required',
        ]);

        // Redirect back if validation fail.

        $imageName = null;

        if ($request->hasFile('image')) {
            $imageName = date('Ymdhis') . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('/uploads/staffs', $imageName);
        }

        User::create([
            'role_id' => $request->role_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'image' => $imageName,
        ]);

        $nurse = Role::where('name', 'Nurse')->first();
        $wardboy = Role::where('name', 'Wardboy')->first();

        if ($request->role_id == $nurse->id) {
            Toastr::success('Nurse Added Successfully');
            return redirect()->route('nurses.index');
        } else if ($request->role_id == $wardboy->id) {
            // Do something when you arrived in this block.
            // Redirect to wardboy page ???
        }

        Toastr::success('Wardboy Added Successfully');

        return redirect()->route('wardboys.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::whereNot('name', 'Admin')->get();

        $genders = ['Male', 'Female', 'Others'];

        return view('admin.pages.staff.create', compact('roles', 'genders'));
    }
}

