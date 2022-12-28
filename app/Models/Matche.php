<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matche extends Model
{
    use HasFactory;
    //protected $table = 'stadiums';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'time',
        'team1_id',
        'team2_id',
        'referee',
        'lineman1',
        'lineman2',
        'stadium_id'
    ];

    // get stadium
    /**
     * Get the stadium that owns the match.
     */
    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function team1()
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2()
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }

}
