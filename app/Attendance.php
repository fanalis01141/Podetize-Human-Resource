<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'time_in', 'time_out', 'undertime', 'overtime','emp_id','attend_date'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
