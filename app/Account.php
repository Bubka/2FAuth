<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
        use SoftDeletes;

        protected $fillable = ['name', 'secret', 'icon'];
}
