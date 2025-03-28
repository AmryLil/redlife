<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_type_id',
        'total_quantity',
    ];

    public function bloodType()
    {
        return $this->belongsTo(BloodTypes::class, 'blood_type_id');
    }

    public function bloodStock()
    {
        return $this->belongsTo(BloodStock::class);
    }

    // Fungsi untuk mengecek apakah darah sudah kedaluwarsa
}
