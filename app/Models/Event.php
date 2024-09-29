<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; 

class Event extends Model
{
    use HasFactory;

    protected $casts = [
        'date_time' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'description',
        'location',
        'date_time',
        'category_id',
        'organizer_id',
        'max_attendees',
        'ticket_price',
        'status',
        'visibility',
        'image',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
