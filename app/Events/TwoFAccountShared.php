<?php

namespace App\Events;

use App\Events\Traits\HasSharingScopes;
use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TwoFAccountShared
{
    use Dispatchable, HasSharingScopes, InteractsWithSockets, SerializesModels;

    public TwoFAccount $twofaccount;

    public User $actor;

    /**
     * @var Collection<int, User>
     */
    public Collection $recipients;

    public string $scope;

    /**
     * @param  Collection<int, User>  $recipients
     */
    public function __construct(TwoFAccount $twofaccount, User $actor, Collection $recipients, string $scope)
    {
        $this->twofaccount = $twofaccount;
        $this->actor       = $actor;
        $this->recipients  = $recipients;
        $this->scope       = $scope;

        Log::info(sprintf('TwoFAccountShared event dispatched for TwoFAccount #%s', $twofaccount->id));
    }
}
