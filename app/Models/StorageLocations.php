<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageLocations extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address'];

    public function bloodStocks()
    {
        return $this->hasMany(BloodStock::class, 'storage_location_id');
    }
}
