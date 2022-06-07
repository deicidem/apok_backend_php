<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskResultView extends Model
{

    public function file() {
        return $this->hasOne(File::class);
    }

    public function taskResult() {
        return $this->belongsTo(TaskResult::class);
    }

    protected $table = 'task_result_views';

    protected $fillable = [
        'title',
        'type',
        'geography',
    ];
    use HasFactory;
}
