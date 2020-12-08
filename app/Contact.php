<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    //
    protected $fillable = [
        'user_id', 'email', 'address', 'contact_number', 'emergency_contact_person', 'emergency_contact_number'
    ];
}
