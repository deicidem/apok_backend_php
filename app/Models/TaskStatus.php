<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    use HasFactory;

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    protected $table = 'task_statuses';

    protected $fillable = [
        'name'
    ];
}
