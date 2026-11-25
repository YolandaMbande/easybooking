<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketType extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'quantity',
        'sold',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'sold' => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
