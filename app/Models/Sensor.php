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

    

    protected $table = 'sensors';

    protected $fillable = [
        'name',
        'description'
    ];
}
