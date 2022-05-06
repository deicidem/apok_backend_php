<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spector extends Model
{
    use HasFactory;

    public function sensor() {
        return $this->belongsTo(Sensor::class);
    }

    protected $table = "spectors";

    protected $fillable = [
        'name',
        'start_w',
        'end_w'
    ];
}
