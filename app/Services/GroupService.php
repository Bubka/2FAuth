<?php

namespace App\Services;

use App\Models\Group;
use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class GroupService
{
    /**
     * @var  \App\Models\User|null
     */
    protected $user;

    /**
     * @var  bool
     */
    protected $withTheAllGroup = false;


    /**
     * Create a new Group service instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = null;
    }

    /**
     * Sets the user on behalf of whom the service act
     * 
     * @param  \App\Models\User  $user
     * @return self
     */
    public function for(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Sets the service to return group collections prepended with the 'All' pseudo group
     * 
     * @return self
     */
    public function withTheAllGroup()
    {
        $this->withTheAllGroup = true;

        return $this;
    }

    /**
     * Get one or multiple group by their primary keys
     *
     * @param  int|array  $ids
     * @return Collection<int, Group>|Group
     * 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\App\Models\Group>
     */
    public function get(mixed $ids)
    {
        /**
         * @var Collection<int, Group>|Group
         */
        $groups = Group::withCount('twofaccounts')->findOrFail($ids);

        if ($groups instanceof Collection) {
            if (! is_null($this->user)) {
                // Authorization check
                if ($this->user->cannot('viewEach', [(new Group), $groups])) {
                    Log::notice(sprintf('User ID #%s cannot view all groups in IDs #%s', $this->user->id, implode(',', $ids)));
                    throw new AuthorizationException();
                }
            }
        }
        else {
            if (! is_null($this->user)) {
                // Authorization check
                if ($this->user->cannot('view', $groups)) {
                    Log::notice(sprintf('User ID #%s cannot view group %s (#%s)', $this->user->id, var_export($groups->name, true), $groups->id));
                    throw new AuthorizationException();
                }
            }
        }

        return $groups;
    }

    /**
     * Returns all existing groups preprended with the 'All' group for the given user
     *
     * @return Collection<int, Group>
     */
    public function all() : Collection
    {
        $groups = ! is_null($this->user)
            ? $this->user->groups()->withCount('twofaccounts')->get()
            : Group::withCount('twofaccounts')->get();

        return $this->withTheAllGroup
            ? self::prependTheAllGroup($groups)
            : $groups;
    }

    /**
     * Returns all accounts of the group
     *
     * @param  \App\Models\Group  $group
     * @return Collection<int, \App\Models\TwoFAccount>
     */
    public function accounts(Group $group) : Collection
    {
        if (! is_null($this->user)) {
            // Authorization check
            if ($this->user->cannot('view', $group)) {
                Log::notice(sprintf('User ID #%s cannot view group ID #%s', $this->user->id, $group->id));
                throw new AuthorizationException();
            }
        }

        return $group->twofaccounts;
    }

    /**
     * Creates a group
     *
     * @param  array  $data
     * @return \App\Models\Group The created group
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function create(array $data) : Group
    {
        if (! is_null($this->user)) {
            // Authorization check
            if ($this->user->cannot('create', Group::class)) {
                Log::notice(sprintf('User ID #%s cannot create groups', $this->user->id));
                throw new AuthorizationException();
            }

            $group = $this->user->groups()->create([
                'name' => $data['name'],
            ]);

            Log::info(sprintf('Group %s created for user ID #%s', var_export($group->name, true), $this->user->id));
            
            return $group;
        }
        else {
            throw new \Exception('Cannot create a group without a user');
        }
    }

    /**
     * Updates a group using a list of values
     *
     * @param  \App\Models\Group  $group The group
     * @param  array  $data The parameters
     * @return \App\Models\Group The updated group
     *
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function update(Group $group, array $data) : Group
    {
        if (! is_null($this->user)) {
            // Authorization check
            if ($this->user->cannot('update', $group)) {
                Log::notice(sprintf('User ID #%s cannot update group %s', $this->user->id, var_export($group->name, true)));
                throw new AuthorizationException();
            }

            $group->update([
                'name' => $data['name'],
            ]);
    
            Log::info(sprintf('Group %s updated by user ID #%s', var_export($group->name, true), $this->user->id));
    
            return $group;
        }
        else {
            throw new \Exception('Cannot update a group without a user');
        }
    }

    /**
     * Deletes one or more groups
     *
     * @param  int|array  $ids group ids to delete
     * @return int The number of deleted
     */
    public function delete($ids) : int
    {
        $ids = is_array($ids) ? $ids : [$ids];
        $groups = Group::findMany($ids);

        if ($groups->count() > 0) {
            if (! is_null($this->user)) {
                // Authorization check
                if ($this->user->cannot('deleteEach', [$groups[0], $groups])) {
                    Log::notice(sprintf('User ID #%s cannot delete all groups in IDs #%s', $this->user->id, implode(',', $ids)));
                    throw new AuthorizationException();
                }
            }

            return Group::destroy($ids);
        }

        return 0;
    }

    /**
     * Assign one or more accounts to a group
     *
     * @param  array|int  $ids accounts ids to assign
     * @param  \App\Models\Group  $group The target group
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\App\Models\TwoFAccount>
     */
    public function assign($ids, Group $group = null) : void
    {
        $ids = is_array($ids) ? $ids : [$ids];
        $twofaccounts = TwoFAccount::findOrFail($ids);

        if (! is_null($this->user)) {
            $group = $group ?? self::defaultGroup($this->user);

            if ($group) {
                // Authorization check on group
                if ($this->user->cannot('update', $group)) {
                    Log::notice(sprintf('User ID #%s cannot assign twofaccounts to group ID #%s', $this->user->id, $group->id));
                    throw new AuthorizationException();
                }

                // Authorization check on twofaccounts
                if ($this->user->cannot('updateEach', [$twofaccounts[0], $twofaccounts])) {
                    Log::notice(sprintf('User ID #%s cannot assign twofaccounts IDs #%s to a group', $this->user->id, implode(',', $ids)));
                    throw new AuthorizationException();
                }

                $group->twofaccounts()->saveMany($twofaccounts);
                $group->loadCount('twofaccounts');
    
                Log::info(sprintf('Twofaccounts IDs #%s assigned to group %s (id #%s)', implode(',', $ids), var_export($group->name, true), $group->id));
            } else {
                Log::info(sprintf('Cannot find a group to assign the TwoFAccounts IDs #%s to', implode(',', $ids)));
            }
        }
        else if ($group) {
            $group->twofaccounts()->saveMany($twofaccounts);
            $group->loadCount('twofaccounts');

            Log::info(sprintf('Twofaccounts IDs #%s assigned to group %s (id #%s)', implode(',', $ids), var_export($group->name, true), $group->id));
        }
        else
        {
            Log::info(sprintf('No group to assign the TwoFAccounts IDs #%s to', implode(',', $ids)));
        }
    }

    /**
     * Prepends the pseudo group named 'All' to a group collection
     *
     * @param  Collection<int, Group>  $groups
     * @return Collection<int, Group>
     */
    private function prependTheAllGroup(Collection $groups) : Collection
    {
        $theAllGroup = new Group([
            'name' => __('commons.all'),
        ]);

        $theAllGroup->id                 = 0;
        $theAllGroup->twofaccounts_count = is_null($this->user)
                                                ? TwoFAccount::count()
                                                : TwoFAccount::where('user_id', $this->user->id)->count();

        return $groups->prepend($theAllGroup);
    }

    /**
     * Determines the default group of the given user
     *
     * @param  \App\Models\User  $user
     * @return \App\Models\Group|null The group or null if it does not exist
     */
    private static function defaultGroup(User $user)
    {
        $id = $user->preferences['defaultGroup'] === -1 ? (int) $user->preferences['activeGroup'] : (int) $user->preferences['defaultGroup'];

        return Group::find($id);
    }
}
