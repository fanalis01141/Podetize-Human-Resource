<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'emp_id', 'reason','total_days','dates', 'emp_name','type','status','sick_leaves_left','vacation_leaves_left','note'
    ];
    //
}
