<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    protected $fillable = [ 'emp_id', 'award_title', 'award_content', 'icon', 'type' ];
}
