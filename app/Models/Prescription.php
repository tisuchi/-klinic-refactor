<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function doctors()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function prescriptionMedicine()
    {
        return $this->hasMany(PrescriptionMedicine::class);
    }

    public function prescriptionTest()
    {
        return $this->hasMany(PrescriptionTest::class);
    }

}
