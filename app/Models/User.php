<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_ORGANIZER = 'organizer';
    const ROLE_ATTENDEE = 'attendee';

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isOrganizer()
    {
        return $this->role === self::ROLE_ORGANIZER;
    }

    public function isAttendee()
    {
        return $this->role === self::ROLE_ATTENDEE;
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public static function roles() {
        return ['admin', 'organizer', 'user'];
    }
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function hasRole($role)
    {
        return $this->role === $role;
    }
}
