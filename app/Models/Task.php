<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function dzz() {
        return $this->belongsTo(Dzz::class);
    }

    public function taskStatus() {
        return $this->belongsTo(TaskStatus::class);
    }

    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'result',
        'task_status_id',
        'dzz_id'
    ];
}
