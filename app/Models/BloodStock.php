<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodStock extends Model
{
    use HasFactory;

    protected $fillable = ['blood_type_id', 'hospital_id', 'quantity'];

    public function bloodType()
    {
        return $this->belongsTo(BloodTypes::class, 'blood_type_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospitals::class, 'hospital_id');
    }
}
