<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //
    protected $fillable = [
        'item_name','quantity', 'emp_id', 'date_issued','description'
    ];
}
