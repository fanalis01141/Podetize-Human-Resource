<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    protected $fillable = [
        'emp_id', 'phic', 'sss', 'pagibig'
    ];
}
