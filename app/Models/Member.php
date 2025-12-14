<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = [
        'name',
        'nickName',
        'father_name',
        'phone',
        'spouse_name',
        'bloodType',
        'kollan_id',
        'kollan_khedmot',
        'image',
        'status'
    ];

    public function memberAssigns()
    {
        return $this->hasMany(MemberAssign::class, 'member_id');
    }

    public function khedmots()
    {
        return $this->hasMany(Khedmot::class);
    }
    public function user()
    {
        return $this->hasManyThrough(User::class, MemberAssign::class, 'member_id', 'id', 'id', 'user_id');
    }
}
