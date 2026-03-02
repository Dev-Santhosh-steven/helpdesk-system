<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SLA extends Model
{
    protected $fillable =[
        'priority',
        'response_time',
        'resolution_time',
    ];

}
