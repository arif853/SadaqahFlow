<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberAssign extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $with = ['user','member'];

    protected $fillable = ['user_id','member_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    
}
