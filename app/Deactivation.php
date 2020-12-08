<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deactivation extends Model
{
    protected $fillable = [
        'emp_id', 'reason'
    ];
}
