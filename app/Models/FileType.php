<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileType extends Model
{
    use HasFactory;

    public function files() {
        return $this->hasMany(File::class);
    }

    protected $table = "file_types";

    protected $fillable = [
        'name'
    ];
}
