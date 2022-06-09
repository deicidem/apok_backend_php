<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanData extends Model
{
    use HasFactory;

    public function type() {
        return $this->belongsTo(PlanDataType::class, 'type_id');
    }

    public function plan() {
        return $this->belongsTo(Plan::class);
    }

    protected $table = 'plan_data';

    protected $fillable = [
        'title',
        'description',
        'type_id'
    ];
}
