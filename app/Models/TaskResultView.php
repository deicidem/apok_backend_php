<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskResultView extends Model
{


    public function preview() {
        return $this->belongsTo(File::class, 'preview_id');
    }
    public function type() {
        return $this->belongsTo(DataType::class, 'type_id');
    }
    public function taskResult() {
        return $this->belongsTo(TaskResult::class, 'task_result_id');
    }

    protected $table = 'task_result_views';

    protected $touches = ['taskResult'];

    protected $fillable = [
        'title',
        'type',
        'geography',
        'preview_id',
        'type_id',
        'task_result_id'
    ];
    use HasFactory;
}
