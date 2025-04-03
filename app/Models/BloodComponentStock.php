<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodComponentStock extends Model
{
    use HasFactory;

    protected $table = 'blood_stock_component';

    protected $fillable = [
        'blood_stock_id',
        'blood_component_id',
        'quantity',
    ];

    public function bloodStock()
    {
        return $this->belongsTo(BloodStock::class, 'blood_stock_id');
    }

    public function bloodComponent()
    {
        return $this->belongsTo(BloodComponent::class, 'blood_component_id');
    }
}
