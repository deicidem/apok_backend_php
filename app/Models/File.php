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
        return $this->belongsTo(Plan::class);
    }
    public function taskResult() {
        return $this->belongsTo(TaskResult::class);
    }
    public function taskResultView() {
        return $this->belongsTo(TaskResultView::class);
    }
    public function taskData() {
        return $this->hasMany(TaskData::class);
    }
    protected $table = 'files';

    protected $fillable = [
        'name',
        'path',
        'dzz_id',
        'type_id',
    ];

}
