<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Event extends Model {
    protected $fillable = ['title','slug','category','summary','description','venue','city','starts_at','cover_image','status','featured'];
    protected function casts(): array { return ['starts_at'=>'datetime','featured'=>'boolean']; }
    public function ticketTypes(): HasMany { return $this->hasMany(TicketType::class); }
    
}
