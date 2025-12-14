<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receive extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable =
    [
        'date',
        'program_id',
        'other_program_name',
        'khedmot_amount',
        'manat_amount',
        'kollan_amount',
        'rent_amount',
        'total_amount',
        'status',
        'comment',
        'submitted_by',
        'collected_by',
        'canceled_by',
        'collected_at',
        'canceled_at',
        'submitted_at',
        'khedmot_ids',
    ];

    public function submitor()
    {
        return $this->belongsTo(User::class, 'submitted_by', 'id');
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collected_by', 'id');
    }

    public function canceller()
    {
        return $this->belongsTo(User::class, 'canceled_by', 'id');
    }

    public function program()
    {
        return $this->belongsTo(ProgramType::class, 'program_id', 'id');
    }
}
