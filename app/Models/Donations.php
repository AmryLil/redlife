<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donations extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing  = false;
    protected $keyType    = 'int';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = random_int(100000, 999999);
        });
    }

    protected $fillable = ['user_id', 'donation_date', 'time', 'location_id', 'status_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function location()
    {
        return $this->belongsTo(DonationLocation::class, 'location_id');
    }
}
