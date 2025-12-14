<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'date',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($programType) {
            $programType->slug = str($programType->name)->slug();
        });

        static::updating(function ($programType) {
            if ($programType->isDirty('name')) {
                $programType->slug = str($programType->name)->slug();
            }
        });
    }
}
