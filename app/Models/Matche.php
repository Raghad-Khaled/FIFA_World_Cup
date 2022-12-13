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
}
