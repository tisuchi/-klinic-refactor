<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Patient::orderBy('id', 'desc')->paginate(10);

        return view('admin.pages.patient.index', compact('patients'));
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'address' => 'required',
            'mobile' => 'required',
            'blood_group' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $imageName = null;

        if ($request->hasFile('patient_image')) {
            $imageName = date('Ymdhis') . '.' . $request->file('patient_image')->getClientOriginalExtension();
            $request->File('patient_image')->storeAs('/uploads/patients', $imageName);
        }

        $patient = new Patient();

        $patient->create([
            'patient_id' => $patient->generatePatientId(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'blood_group' => $request->blood_group,
            'patient_image' => $imageName
        ]);

        Log::Channel('custom')->info("Patient has been created successfully");

        return redirect()->route('patients.index')->with(Toastr::success('Patient has been craeted successfully'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.patient.create');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $patient = Patient::findOrFail($id);

        return view('admin.pages.patient.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $patient = Patient::findOrFail($id);

        $genders = ['Male', 'Female'];

        return view('admin.pages.patient.edit', compact('patient', 'genders'));
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'address' => 'required',
            'mobile' => 'required',
            'blood_group' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $imageName = null;

        if ($request->hasFile('patient_image')) {
            $imageName = date('Ymdhis') . '.' . $request->file('patient_image')->getClientOriginalExtension();
            $request->File('patient_image')->storeAs('/uploads/patients', $imageName);
        }

        $patient = Patient::findOrFail($id);

        $patient->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'blood_group' => $request->blood_group,
            'patient_image' => $imageName
        ]);

        return redirect()->route('patients.index')->with(Toastr::success('Patient has been updated successfully'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Patient::findOrFail($id)->delete();

        return redirect()->back()->with(Toastr::error('Patient Deleted Successfully.'));
    }
}

