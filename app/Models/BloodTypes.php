<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodTypes extends Model
{
    use HasFactory;

    protected $fillable = ['group', 'rhesus'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function bloodStocks()
    {
        return $this->hasMany(BloodStock::class);
    }

    public function getFullTypeAttribute()
    {
        return $this->group . $this->rhesus;
    }
}
