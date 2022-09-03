<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Doctor;
use App\Models\Patient;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatientAdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admissions = Admission::orderBy('id', 'desc')->paginate(10);

        return view('admin.pages.patient.admission.index', compact('admissions'));
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
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'admission_date' => 'required',
            'discharge_date' => 'required',
            'package' => 'required',
            'insurance' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'allergies' => 'required',
            'tendancy' => 'required',
            'heart_diseases' => 'required',
            'high_BP' => 'required',
            'accident' => 'required',
            'diabetic' => 'required',
            'infection' => 'required',
            'quota' => 'required',
            'others' => 'required',
            'guardian_name' => 'required',
            'guardian_relation' => 'required',
            'guardian_contact' => 'required',
            'guardian_address' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $patient = Patient::where('patient_id', $request->patient_id)->firstOrFail();

        if (!$patient) {
            \toastr()->error('Invalid Patient ID');

            return redirect()->back()->withInput();
        }

        $admission = new Admission();
        $admission->create([
            'admission_id' => $admission->generateAdmissionId(),
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'admission_date' => $request->admission_date,
            'discharge_date' => $request->discharge_date,
            'package' => $request->package,
            'insurance' => $request->insurance,
            'height' => $request->height,
            'weight' => $request->weight,
            'allergies' => $request->allergies,
            'tendancy' => $request->tendancy,
            'heart_diseases' => $request->heart_diseases,
            'high_BP' => $request->high_BP,
            'accident' => $request->accident,
            'diabetic' => $request->diabetic,
            'infection' => $request->infection,
            'quota' => $request->quota,
            'others' => $request->others,
            'guardian_name' => $request->guardian_name,
            'guardian_relation' => $request->guardian_relation,
            'guardian_contact' => $request->guardian_contact,
            'guardian_address' => $request->guardian_address,
        ]);

        Toastr::success('Admission information has been recorded Successfully', 'success');

        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $patients = Patient::get();
        $doctors = Doctor::get();

        return view('admin.pages.patient.admission.create', compact('patients', 'doctors'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $admission = Admission::findOrFail($id);

        return view('admin.pages.patient.admission.show', compact('admission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admission = Admission::findOrFail($id);

        $doctors = Doctor::get();

        $answers = ['No', 'Yes'];

        return view('admin.pages.patient.admission.edit', compact('admission', 'doctors', 'answers'));
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
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'admission_date' => 'required',
            'discharge_date' => 'required',
            'package' => 'required',
            'insurance' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'allergies' => 'required',
            'tendancy' => 'required',
            'heart_diseases' => 'required',
            'high_BP' => 'required',
            'accident' => 'required',
            'diabetic' => 'required',
            'infection' => 'required',
            'quota' => 'required',
            'others' => 'required',
            'guardian_name' => 'required',
            'guardian_relation' => 'required',
            'guardian_contact' => 'required',
            'guardian_address' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $admission = Admission::findOrFail($id);
        $admission->update([
            'doctor_id' => $request->doctor_id,
            'admission_date' => $request->admission_date,
            'discharge_date' => $request->discharge_date,
            'package' => $request->package,
            'insurance' => $request->insurance,
            'height' => $request->height,
            'weight' => $request->weight,
            'allergies' => $request->allergies,
            'tendancy' => $request->tendancy,
            'heart_diseases' => $request->heart_diseases,
            'high_BP' => $request->high_BP,
            'accident' => $request->accident,
            'diabetic' => $request->diabetic,
            'infection' => $request->infection,
            'quota' => $request->quota,
            'others' => $request->others,
            'guardian_name' => $request->guardian_name,
            'guardian_relation' => $request->guardian_relation,
            'guardian_contact' => $request->guardian_contact,
            'guardian_address' => $request->guardian_address,
        ]);

        Toastr::success('Admission information has been updated Successfully');

        return redirect()->route('admissions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Admission::findOrFail($id)->delete();

        return redirect()->back()->with(Toastr::error('Admission Record Deleted Successfully'));
    }
}
