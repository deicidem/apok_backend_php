<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    public function satelite() {
        return $this->belongsTo(Satelite::class);
    }

    public function spectors() {
        return $this->hasMany(Spector::class);
    }

    public function dzzs() {
        return $this->hasMany(Dzz::class);
    }

    protected $table = 'sensors';

    protected $fillable = [
        'name',
        'description'
    ];
}
