<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    use HasFactory;

    protected $fillable = ['hospital_id', 'blood_type_id', 'desc', 'quantity', 'status_id'];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function bloodType()
    {
        return $this->belongsTo(BloodTypes::class);
    }

    public function requestStatus()
    {
        return $this->belongsTo(BloodRequestType::class);
    }
}
