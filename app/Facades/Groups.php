<?php

namespace App\Facades;

use App\Models\Group;
use App\Models\User;
use App\Services\GroupService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @see GroupService
 *
 * @method static void assign(array|int $ids, User $user, Group|int|string|null $targetGroup = null)
 * @method static Collection<int, Group> prependTheAllGroup(Collection<int, Group> $groups, User $user)
 * @method static void setUser(Collection<int, Group> $groups, User $user)
 */
class Groups extends Facade
{
    protected static function getFacadeAccessor()
    {
        return GroupService::class;
    }
}
