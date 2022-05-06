<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dzz extends Model
{
    use HasFactory;


    protected $table = 'dzzs';

    public function processingLevel() {
        return $this->belongsTo(ProcessingLevel::class);
    }

    public function sensor() {
        return $this->belongsTo(Sensor::class);
    }

    public function files() {
        return $this->hasMany(File::class);
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    protected $fillable = [
        'name',
        'date',
        'geography',
        'round',
        'route',
        'cloudinsess',
        'description',
    ];
}
