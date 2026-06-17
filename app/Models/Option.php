<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Option
 *
 * @property int $id
 * @property string $key
 * @property string $value
 */
#[Fillable(['key', 'value'])]
class Option extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Casts.
     *
     * @var array<string, string>
     */
    protected $casts = [];
}
