<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function users() {
        return $this->belongsToMany(User::class, 'group_user');
    }
    public function type() {
        return $this->belongsTo(GroupType::class, 'type_id');
    }
    protected $table = 'groups';
    protected $fillable = [
        'title',
        'type_id',
        'owner_id'
    ];
}
