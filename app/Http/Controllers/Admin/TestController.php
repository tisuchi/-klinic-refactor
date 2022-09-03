<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssignTest;
use App\Models\Test;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tests = Test::get();

        return view('admin.pages.diagonistic.test.index', compact('tests'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'procedure' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $filename = '';

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = date('Ymdhms') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/uploads', $filename);
        }

        Test::create([
            'name' => $request->name,
            'price' => $request->price,
            'procedure' => $request->procedure,
            'description' => $request->description,
            'image' => $filename
        ]);

        Toastr::success('Test added Successfully', 'success');

        return redirect()->route('tests.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.diagonistic.test.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        $test = Test::findOrFail($id);

        return view('admin.pages.diagonistic.test.edit', compact('test'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'procedure' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $test = Test::findOrFail($id);

        $filename = $test->image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = date('Ymdhms') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('/uploads', $filename);
        }

        $test->update([
            'name' => $request->name,
            'price' => $request->price,
            'procedure' => $request->procedure,
            'description' => $request->description,
            'image' => $filename
        ]);

        Toastr::success('Test updated Successfully', 'success');

        return redirect()->route('tests.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        Test::findOrFaill($id)->delete();

        Toastr::error('Test deleted Successfully');

        return redirect()->route('tests.index');
    }

    public function assignTestIndex()
    {
        $assigntests = AssignTest::all();
        return view('admin.pages.diagonistic.test.assign.index', compact('assigntests'));
    }

    public function assignTestCreate()
    {
        $tests = Test::all();
        return view('admin.pages.diagonistic.test.assign.create', compact('tests'));
    }

    public function assignTestStore(Request $request)
    {
        $test_id = $request->test_id;
        foreach ($test_id as $key => $item)
            AssignTest::create([
                'patient_id' => $request->patient_id,
                'test_id' => $test_id[$key],
                'note' => $request->note,
                'assigned_by' => ucfirst(auth()->user()->username)
            ]);

        Toastr::success('Test added Successfully', 'success');
        return redirect()->route('assign.test.index');
    }

}
