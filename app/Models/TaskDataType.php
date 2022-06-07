<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDataType extends Model
{
    use HasFactory;

    public function data() {
        return $this->hasMany(TaskData::class);
    }

    protected $table = "task_data_types";

    protected $fillable = [
        'title'
    ];
}
