<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donations extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'donation_date', 'time', 'location_id', 'status_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
