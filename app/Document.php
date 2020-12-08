<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'emp_id', 'employee_packet', 'pay_stubs', 'metrics_sheet', 'evaluation_sheet' , 'warning_memo'
    ];
}
