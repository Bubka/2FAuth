<?php

namespace App\Events;

use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TwoFAccountOwnershipTransferred
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TwoFAccount $twofaccount;

    public User $previousOwner;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TwoFAccount $twofaccount, User $previousOwner)
    {
        $this->twofaccount   = $twofaccount;
        $this->previousOwner = $previousOwner;

        Log::info(sprintf('TwoFAccountOwnershipTransferred event dispatched for TwoFAccount #%s', $twofaccount->id));
    }
}
