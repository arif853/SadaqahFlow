<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'address',
        'phone',
        'bloodType',
        'status',
    ];

    /**
     * Get all of the khedmots for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function khedmots()
    {
        return $this->hasMany(Khedmot::class);
    }

    public function memberAssigns()
    {
        return $this->hasMany(MemberAssign::class);
    }

    public function members()
    {
        return $this->hasManyThrough(Member::class, MemberAssign::class, 'user_id', 'id', 'id', 'member_id');
    }

    public function assignMember($member)
    {
        return $this->memberAssigns()->create(['member_id' => $member->id, 'user_id' => $this->id]);
    }

    public function fundCollections()
    {
        return $this->hasMany(FundCollection::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function loginLogs()
    {
        return $this->hasMany(UserLog::class);
    }

    public function lastLogin()
    {
        return $this->loginLogs()->latest('last_login_at')->first();
    }

    public function removeMemberAssign($memberId)
    {
        return $this->memberAssigns()->where('member_id', $memberId)->delete();
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('Super Admin');
    }

    public function isAdmin()
    {
        return $this->hasRole('Admin');
    }

}
