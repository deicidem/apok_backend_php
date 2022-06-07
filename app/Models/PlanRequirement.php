<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanRequirement extends Model
{
    use HasFactory;

    public function plan() {
        return $this->belongsTo(Plan::class);
    }

    protected $table = 'plan_requirements';

    protected $fillable = [
        'title',
        'description'
    ];
}
