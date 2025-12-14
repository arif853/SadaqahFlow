<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'paid_by',
        'paid_at',
        'date',
        'pay_to',
        'khedmot_amount',
        'manat_amount',
        'kalyan_amount',
        'rent_amount',
        'total_paid',
        'left_amount',
        'comment',
        'payment_status',
    ];

    public function payBy()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}
