<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AppointmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appointments = Appointment::with('departments', 'doctors')->paginate(10);

        return view('admin.pages.appointment.index', compact('appointments'));
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
            'appointment_id' => 'required',
            'patient_id' => 'required',
            'department_id' => 'required',
            'doctor_id' => 'required',
            'date' => 'required',
            'problem' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Appointment::create([
            'appointment_id' => strtoupper(Str::random(10)),
            'patient_id' => strtoupper($request->patient_id),
            'department_id' => $request->department_id,
            'doctor_id' => $request->doctor_id,
            'date' => $request->date,
            'problem' => $request->problem,
            'status' => $request->status
        ]);

        Toastr::success('Appointment Created Successfully');

        return redirect()->route('appointment.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::get();

        $doctors = Doctor::get();

        return view('admin.pages.appointment.create', compact('departments', 'doctors'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);

        $departments = Department::get();

        return view('admin.pages.appointment.edit', compact('departments', 'appointment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $validator = $request->validate([
            'appointment_id' => 'required',
            'patient_id' => 'required',
            'department_id' => 'required',
            'doctor_id' => 'required',
            'date' => 'required',
            'problem' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Appointment::findOrFail($id)
            ->update([
                'p_id' => $request->p_id,
                'd_department' => $request->d_department,
                'doctor' => $request->doctor,
                'date' => $request->date,
                'problem' => $request->problem,
                'status' => $request->status
            ]);

        Toastr::success('Appointment Updated Successfully');

        return redirect()->route('appointment.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Appointment::findOrFail($id)->delete();

        Toastr::success('Appointment Deleted Successfully');

        return redirect()->route('appointment.index');
    }
}
