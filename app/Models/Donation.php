<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id', 'tanggal_donor', 'lokasi_donor', 'jumlah_donor', 'status_donor'
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }
}
