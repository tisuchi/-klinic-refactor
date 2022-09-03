<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssignTest;
use App\Models\Test;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class AssignTestController extends Controller
{
    public function index()
    {
        $assignTests = AssignTest::paginate(10);

        return view('admin.pages.diagonistic.test.assign.index', compact('assignTests'));
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'note' => 'required',
            'patient_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $testIds = $request->test_id;

        foreach ($testIds as $testId) {
            AssignTest::create([
                'patient_id' => $request->patient_id,
                'test_id' => $testId,
                'note' => $request->note,
                'assigned_by' => auth()->user()->username,
            ]);
        }

        Toastr::success('Test added Successfully', 'success');

        return redirect()->route('assign.test.index');
    }

    public function create()
    {
        $tests = Test::get();

        return view('admin.pages.diagonistic.test.assign.create', compact('tests'));
    }

}
