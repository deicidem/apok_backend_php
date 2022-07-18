<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory;
    public function user() {
        return $this->belongsTo(User::class);
    }
    protected $touches = ['user'];
protected $table = 'user_logs';
protected $fillable = ['message', 'type', 'user_id'];
}
