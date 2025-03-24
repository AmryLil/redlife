<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_type_id',
        'storage_location_id',
        'donation_id',
        'quantity',
        'collection_date',
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

    // Fungsi untuk mengecek apakah darah sudah kedaluwarsa
    public function isExpired(): bool
    {
        return now()->greaterThan($this->expiry_date);
    }
}
