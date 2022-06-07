<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskResult extends Model
{
    use HasFactory;

    public function task() {
        return $this->belongsTo(Task::class);
    }

    public function files() {
        return $this->hasMany(File::class);
    }

    public function views() {
        return $this->hasMany(TaskResultView::class);
    }

    protected $table = 'task_results';

}
