<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationLocation extends Model
{
    use HasFactory;

    protected $fillable = ['location_name', 'location_detail', 'address', 'url_address', 'cover', 'city'];

    public function donations()
    {
        return $this->hasMany(Donations::class);
    }
}
