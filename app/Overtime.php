<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    protected $fillable = [
        'emp_id', 'task_or_eps', 'department', 'complete_name', 'date', 'hours', 'for_approval', 'status'
    ];
}
