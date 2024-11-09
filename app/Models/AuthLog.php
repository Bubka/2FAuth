<?php

/**
 * The MIT License (MIT)
 * Copyright (c) 2024 Bubka
 * Copyright (c) 2024 Anthony Rappa
 * Copyright (c) 2017 Yaakov Dahan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and
 * associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace App\Models;

use Database\Factories\AuthLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $authenticatable_type
 * @property int $authenticatable_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon|null $login_at
 * @property bool $login_successful
 * @property \Illuminate\Support\Carbon|null $logout_at
 * @property bool $cleared_by_user
 * @property string|null $guard
 * @property string|null $method
 * @property string|null $login_method
 * @property-read Model|\Eloquent $authenticatable
 *
 * @mixin \Eloquent
 *
 * @method static \Database\Factories\AuthLogFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|AuthLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|AuthLog whereAuthenticatableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthLog whereAuthenticatableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthLog whereClearedByUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthLog whereGuard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthLog whereLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthLog whereLoginMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthLog whereLoginSuccessful($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthLog whereLogoutAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuthLog whereUserAgent($value)
 */
class AuthLog extends Model
{
    /**
     * @use HasFactory<AuthLogFactory>
     */
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'ip_address',
        'user_agent',
        'login_at',
        'login_successful',
        'logout_at',
        'cleared_by_user',
        'guard',
        'login_method',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'cleared_by_user'  => 'boolean',
        'login_successful' => 'boolean',
        'login_at'         => 'datetime',
        'logout_at'        => 'datetime',
    ];

    /**
     * MorphTo relation to get the associated authenticatable user
     *
     * @return MorphTo<\Illuminate\Database\Eloquent\Model, AuthLog>
     */
    public function authenticatable()
    {
        return $this->morphTo();
    }

    /**
     * Compare 2 Authentications
     */
    public function equals(self $other) : bool
    {
        return $this->ip_address === $other->ip_address &&
            $this->user_agent === $other->user_agent &&
            $this->login_at == $other->login_at &&
            $this->login_successful == $other->login_successful &&
            $this->logout_at == $other->logout_at &&
            $this->cleared_by_user == $other->cleared_by_user &&
            $this->guard == $other->guard &&
            $this->login_method == $other->login_method;
    }
}
