<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanDataType extends Model
{
    use HasFactory;

    public function data() {
        return $this->hasMany(PlanData::class);
    }

    protected $table = 'plan_data_types';

    protected $fillable = [
        'name'
    ];

}
