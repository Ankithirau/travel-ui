<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus_details extends Model
{
    use HasFactory;

    protected $table = 'bus_details';

    protected $hidden = [
        'dpassword'
    ];
}