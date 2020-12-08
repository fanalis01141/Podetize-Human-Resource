<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcements extends Model
{
    protected $fillable = ['title' , 'content','emp_id','department','position'];
}
