<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationStatus extends Model
{
    use HasFactory;

    protected $fillable = ['status'];

    public function donor()
    {
        return $this->hasMany(Donations::class);
    }
}
