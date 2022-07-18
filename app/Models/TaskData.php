<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskData extends Model
{
    use HasFactory;
    public function task() {
        return $this->belongsTo(Task::class);
    }
    public function type() {
        return $this->belongsTo(DataType::class, 'type_id');
    }
    public function file() {
        return $this->belongsTo(File::class, );
    }
    public function planData() {
        return $this->belongsTo(PlanData::class);
    }
    protected $table = "task_data";

    protected $fillable = [
        'title',
        'geography',
        'text',
        'file_id',
        'type_id',
        'task_id',
        'plan_data_id'
    ];

}
