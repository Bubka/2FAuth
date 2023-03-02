<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Group
 *
 * @method static \Database\Factories\GroupFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUserId($value)
 */
	class Group extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Option
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Option newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Option newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Option query()
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereValue($value)
 */
	class Option extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TwoFAccount
 *
 * @method static \Database\Factories\TwoFAccountFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount ordered(string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereAlgorithm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereCounter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereDigits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereLegacyUri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereOrderColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereOtpType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TwoFAccount whereUserId($value)
 */
	class TwoFAccount extends \Eloquent implements \Spatie\EloquentSortable\Sortable {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePreferences($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \App\Models\WebAuthnAuthenticatable, \Laragear\WebAuthn\Contracts\WebAuthnAuthenticatable {}
}

