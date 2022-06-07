<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function taskStatus() {
        return $this->belongsTo(TaskStatus::class);
    }

    public function result() {
        return $this->hasOne(TaskResult::class);
    }

    public function data() {
        return $this->hasMany(TaskData::class);
    }

    public function plan() {
        return $this->belongsTo(Plan::class);
    }

    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'task_status_id',
        'plan_id'
    ];
}
