<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'daily_rate', 'bi_weekly_rate' , 'monthly_rate' , 'emp_id', 'add_or_ded_daily',
        'add_or_ded_biweekly', 'add_or_ded_monthly', 'status','note'
    ];
}
