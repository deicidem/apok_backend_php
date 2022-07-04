<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SateliteType extends Model
{
    use HasFactory;

    public function satelites() {
        return $this->hasMany(Satelite::class, 'type_id');
    }

    protected $table = "satelite_types";

    protected $fillable = [
        'name'
    ];
}
