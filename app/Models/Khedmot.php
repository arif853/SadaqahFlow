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

    /**
     * Scope records to what $user is allowed to see: everything for
     * Super Admin/Admin (optionally narrowed to one collector via
     * $requestedUserId), or only their own records otherwise.
     *
     * Centralizes the admin-vs-own-records rule that was previously
     * copy-pasted across KhedmotController and ReportController.
     */
    public function scopeVisibleTo($query, User $user, $requestedUserId = null)
    {
        if ($user->isAdminLevel()) {
            return $requestedUserId ? $query->where('user_id', $requestedUserId) : $query;
        }

        return $query->where('user_id', $user->id);
    }

    /**
     * Common date/name filters reused across khedmot listing/search/report endpoints.
     */
    public function scopeFilterBy($query, $date = null, $name = null)
    {
        return $query
            ->when($date, fn ($q) => $q->where('date', $date))
            ->when($name, fn ($q) => $q->whereHas('member', function ($q2) use ($name) {
                $q2->where('name', 'like', '%'.$name.'%')
                    ->orWhere('kollan_id', 'like', '%'.$name.'%');
            }));
    }
}
