<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'seat_number',
        'match_id',
        'user_id'
    ];

    // get match
    /**
     * Get the match that owns the ticket.
     */
    public function match()
    {
        return $this->belongsTo(Matche::class);
    }
}
