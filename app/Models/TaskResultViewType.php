<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskResultViewType extends Model
{

    public function views() {
        return $this->hasMany(TaskResultView::class);
    }

    protected $table = 'task_result_view_types';

    protected $fillable = [
        'title',
    ];
    use HasFactory;
}
