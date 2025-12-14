<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Khedmot extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'date',
        'slug',
        'member_id',
        'program_id',
        'other_program_name',
        'khedmot_amount',
        'manat_amount',
        'kalyan_amount',
        'rent_amount',
        'comment',
        'status',
        'user_id',
        'is_collected',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($khedmot) {
            $khedmot->slug = Str::slug($khedmot->name);
        });
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function program()
    {
        return $this->belongsTo(ProgramType::class, 'program_id');
    }
}
