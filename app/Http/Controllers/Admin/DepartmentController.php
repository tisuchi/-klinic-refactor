<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::paginate(10);

        return view('admin.pages.department.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Department::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        Toastr::success('Department Added Successfully');

        return redirect()->back();
    }

    public function show(string $id)
    {
        $department = Department::findOrFail($id);

        return view('admin.pages.department.view', compact('department'));
    }

    public function edit(string $id)
    {
        $department = Department::findOrFail($id);

        return view('admin.pages.department.edit', compact('department'));
    }

    public function update(Request $request, $id)
    {
        $validator = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $department = Department::findOrFail($id);

        $imageName = $department->image;
        if ($request->hasFile('department_image')) {
            $imageName = date('Ymdhis') . '.' . $request->file('department_image')->getClientOriginalExtension();
            $request->file('department_image')->storeAs('/departments', $imageName);
        }

        $department->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imageName
        ]);

        Toastr::success('Department Updated Successfully', 'success');

        return redirect()->route('show.department');
    }

    public function delete(string $id)
    {
        Department::findOrFail($id)->delete();

        Toastr::error('Department Deleted Successfully');

        return redirect()->back();
    }
}
