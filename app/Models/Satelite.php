<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satelite extends Model
{
    use HasFactory;

    public function sensors() {
        return $this->hasMany(Sensor::class);
    }

    public function type() {
        return $this->belongsTo(SateliteType::class);
    }

    protected $table = "satelites";

    protected $fillable = [
        'name',
        'description'
    ];
}
