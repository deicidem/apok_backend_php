<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    public function type() {
        return $this->belongsTo(FileType::class);
    }
    public function dzz() {
        return $this->hasOne(Dzz::class);
    }
    public function plan() {
        return $this->hasOne(Plan::class);
    }
    public function taskResult() {
        return $this->hasOne(TaskResult::class);
    }
    public function taskResultView() {
        return $this->hasOne(TaskResultView::class);
    }
    public function taskData() {
        return $this->hasMany(TaskData::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    protected $table = 'files';
    protected $touches = ['taskResult'];
    protected $fillable = [
        'name',
        'path',
        'type_id',
        'user_id'
    ];

}
