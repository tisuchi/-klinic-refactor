<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Test;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PrescriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $prescriptions = Prescription::with('patient')->paginate(10);

        return view('admin.pages.prescription.index', compact('prescriptions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            // Add your rules here
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $prescription = Prescription::create([
            'doctor_id' => auth()->user()->id,     // Check your business logic please.
            'patient_id' => $request->patient_id,
            'weight' => $request->weight,
            'blood_pressure' => $request->blood_pressure,
            'reference' => $request->reference,
            'fees' => $request->fees,
            'patient_note' => $request->patient_note,
            'complain' => $request->complain,
            'insurance' => $request->insurance,
        ]);

        foreach ($request->medicine['id'] as $key => $medicineId) {
            $prescription->prescriptionMedicine()->sync([
                'medicine_id' => $medicineId,
                'does' => $request->medicine['medicine_instruction'][$key],
                'days' => $request->medicine['days'][$key],
            ]);
        }

        foreach ($request->test['id'] as $prescriptionTestId) {
            $prescription->prescriptionTest()->sync([
                'test_id' => $prescriptionTestId,
            ]);
        }

        Toastr::success('Prescription Created Successfully.');

        return redirect()->route('prescription.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return view
     */
    public function create()
    {
        $patient = Patient::where('patient_id', request()->patient_id)->firstOrFail();

        $doctor = Doctor::get();

        $medicines = Medicine::get();

        $tests = Test::get();

        return view('admin.pages.prescription.create', compact('doctor', 'medicines', 'tests', 'patient'));
    }
}
