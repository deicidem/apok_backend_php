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

    public function file() {
        return $this->belongsTo(File::class);
    }

    protected $table = 'plans';

    protected $fillable = [
        'title',
        'description',
        'excerpt'
    ];
}
