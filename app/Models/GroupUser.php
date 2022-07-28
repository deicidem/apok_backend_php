<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function group() {
        return $this->belongsTo(Group::class);
    }
    public $timestamps = false;
    protected $table = 'group_user';
    protected $fillable = [
        'user_id',
        'group_id',
        'verified',
    ];
}
