<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Buses extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    protected $guards = 'bus';

    protected $table = 'bus_details';

    protected $primaryKey = 'operator_id';

    // protected $fillable = ['tokens'];

    protected $hidden = [
        'dpassword'
    ];
}
