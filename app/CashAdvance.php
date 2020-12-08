<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CashAdvance extends Model
{
    protected $fillable = [
        'emp_id', 'request', 'months_to_pay', 'date_issued', 'reason', 'ded_per_pay'
    ];

    protected $table = 'cashadvances';
}
