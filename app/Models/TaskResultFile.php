<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskResultFile extends Model
{
    use HasFactory;

    public function taskResult() {
        return $this->belongsTo(Task::class);
    }

    public function file() {
        return $this->belongsTo(File::class);
    }

    protected $touches = ['taskResult'];

    protected $table = 'task_result_files';

    protected $fillable = ['name'];

}
