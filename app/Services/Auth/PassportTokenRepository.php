<?php

/**
 * The MIT License (MIT)
 * Copyright (c) Bubka
 * Copyright (c) Taylor Otwell (https://github.com/laravel/passport)
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

namespace App\Services\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Date;
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\Token;

/**
 * Laravel Passport v13 Token Repository is deprecated since v13
 */
class PassportTokenRepository
{
    /**
     * Get a token by the given user ID and token ID.
     *
     * @param  OAuthenticatable  $user
     */
    public function findForUser(string $id, Authenticatable $user) : ?Token
    {
        return $user->tokens()
            ->with('client')
            ->where('revoked', false)
            ->where('expires_at', '>', Date::now())
            ->find($id);
    }

    /**
     * Get the token instances for the given user ID.
     *
     * @param  OAuthenticatable  $user
     * @return Collection<int, Token>
     */
    public function forUser(Authenticatable $user) : Collection
    {
        return $user->tokens()
            ->with('client')
            ->where('revoked', false)
            ->where('expires_at', '>', Date::now())
            ->get();
    }
}
