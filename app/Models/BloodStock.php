<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodStock extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'blood_type_id',
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

    public function scopeTotalByBloodType($query)
    {
        return $query
            ->selectRaw('blood_type_id, SUM(quantity) as total_quantity')
            ->groupBy('blood_type_id');
    }

    public function bloodComponentStocks()
    {
        return $this->hasMany(BloodComponentStock::class, 'blood_stock_id');
    }

    public function bloodComponents()
    {
        return $this
            ->belongsToMany(BloodComponent::class, 'blood_stock_component')
            ->withPivot('quantity');
    }
}
