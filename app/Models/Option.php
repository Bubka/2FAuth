<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Option extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var [type]
     */
    protected $fillable = [
        'key',
        'value',
    ];


    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * Casts.
     *
     * @var array
     */
    protected $casts = [];

}