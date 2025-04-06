<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequestType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class);
    }
}
