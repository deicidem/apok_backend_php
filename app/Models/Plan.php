<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    public function data() {
        return $this->hasMany(PlanData::class);
    }

    public function requirements() {
        return $this->hasMany(PlanRequirement::class);
    }

    public function preview() {
        return $this->belongsTo(File::class, 'preview_id');
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    protected $table = 'plans';

    protected $fillable = [
        'title',
        'description',
        'excerpt',
        'preview_id'
    ];
}
