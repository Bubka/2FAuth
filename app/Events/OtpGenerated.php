<?php

namespace App\Events;

use App\Models\Dto\HotpDto;
use App\Models\Dto\TotpDto;
use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class OtpGenerated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TwoFAccount $twofaccount;

    public User $requester;

    public User $owner;

    public TotpDto|HotpDto $otpDto;

    /**
     * Create a new event instance.
     */
    public function __construct(TwoFAccount $twofaccount, TotpDto|HotpDto $otpDto)
    {
        $this->twofaccount = $twofaccount;
        $this->requester   = Auth::user();
        $this->owner       = $twofaccount->user ?? Auth::user(); // We used the logged in user as owner if the 2FA account is not persisted
        $this->otpDto      = $otpDto;
    }
}
