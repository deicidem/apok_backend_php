<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    public function type() {
        return $this->belongsTo(DataType::class);
    }
    public function dzz() {
        return $this->hasOne(Dzz::class);
    }
    public function plan() {
        return $this->hasOne(Plan::class);
    }
    public function taskResultFile() {
        return $this->hasOne(TaskResultFile::class);
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
    protected $touches = ['taskResultFile'];
    protected $fillable = [
        'name',
        'path',
        'type_id',
        'user_id'
    ];

}
