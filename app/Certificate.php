<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'emp_id', 'cert_title', 'cert_image', 'cert_date_awarded', 'type','emp_name',
    ];
}
