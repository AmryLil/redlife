<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['hospital_id', 'donation_location_id', 'date', 'time'];

    public function hospital()
    {
        return $this->belongsTo(Hospitals::class);
    }

    public function location()
    {
        return $this->belongsTo(DonationLocation::class);
    }
}
