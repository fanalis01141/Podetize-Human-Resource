<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Veem extends Model
{
    protected $fillable = [
        'user_id', 'veem_email', 'complete_bank_account_name', 'veem_mobile_number'
    ];
}
