<?php

// app/Models/BloodComponent.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'blood_type_id',
    ];

    public function bloodStocks()
    {
        return $this
            ->belongsToMany(BloodStock::class, 'blood_stock_component')
            ->withPivot(columns: 'quantity')
            ->withTimestamps();
    }
}
