<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'emp_id','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'
    ];
}
