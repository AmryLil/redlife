<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodStockDetail extends Model
{
    protected $fillable = [
        'blood_type_id',
        'blood_stock_id',
        'storage_location_id',
        'donation_id',
        'quantity',
        'expiry_date',
        'status',
        'blood_component'
    ];

    public function bloodType()
    {
        return $this->belongsTo(BloodTypes::class, 'blood_type_id');
    }

    public function storageLocation()
    {
        return $this->belongsTo(StorageLocations::class, 'storage_location_id');
    }

    public function donations()
    {
        return $this->belongsTo(Donations::class, 'donation_id');
    }

    public function isExpired(): bool
    {
        return now()->greaterThan($this->expiry_date);
    }
}
