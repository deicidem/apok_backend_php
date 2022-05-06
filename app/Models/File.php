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
        return $this->belongsTo(Dzz::class);
    }
    protected $table = 'files';

    protected $fillable = [
        'name',
        'path',
        'dzz_id',
        'file_type_id'
    ];

}
