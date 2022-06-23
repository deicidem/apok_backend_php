<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function taskStatus() {
        return $this->belongsTo(TaskStatus::class, 'status_id');
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
    public function user() {
        return $this->belongsTo(user::class);
    }
    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'status_id',
        'plan_id',
        'user_id'
    ];
}
