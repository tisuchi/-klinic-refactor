<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Role;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::paginate(10);

        return view('admin.pages.roles.index_role', compact('roles'));
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required',
            'permission_ids' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role = Role::create([
            'name' => $name = $request->name,
            'slug' => Str::slug($name),
        ]);

        $role->permissions()->sync($request->permission_ids);
    }

    public function create()
    {
        $modules = Module::with('permissions')->get();

        return view('admin.pages.roles.create_role', compact('modules'));
    }

    public function show(string $roleId)
    {
        $role = Role::findOrFail($roleId);

        return view('admin.pages.roles.details', compact('role'));
    }

    public function edit(string $roleId)
    {
        $role = Role::findOrFail($roleId);

        $modules = Module::with('permissions')->get();

        $roles = Role::select('id', 'name')->orderBy('id', 'desc')->get();

        return view('admin.pages.roles.edit_role', compact('role', 'roles', 'modules'));
    }

    /**
     * @param Request $request
     * @param string $roleId
     * @return RedirectResponse
     */
    public function update(Request $request, string $roleId): RedirectResponse
    {
        $validator = $request->validate([
            'name' => 'required',
            'permission_ids' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role = Role::where('id', $roleId)->firstOrFail();

        $role->update([
            'name' => $name = $request->name,
            'slug' => Str::slug($name),
        ]);

        $role->permissions()->sync($request->permission_ids);

        return redirect()->route('role.list')->with('success', 'Role Updated Successfully');
    }

    public function delete(string $roleId)
    {
        Role::findOrFail($roleId)->delete();

        Toastr::success('Role Deleted Successfully', 'success');

        return redirect()->back();
    }
}
