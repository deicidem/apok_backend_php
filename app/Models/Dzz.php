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

    public function directory() {
        return $this->belongsTo(File::class, 'directory_id');
    }
    public function preview() {
        return $this->belongsTo(File::class, 'preview_id');
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
        'preview_id',
        'directory_id',
    ];
}
