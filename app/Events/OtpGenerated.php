<?php

namespace App\Events;

use App\Models\Dto\HotpDto;
use App\Models\Dto\TotpDto;
use App\Models\TwoFAccount;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class OtpGenerated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\Models\TwoFAccount
     */
    public $twofaccount;

    /**
     * @var \App\Models\User
     */
    public $requester;

    /**
     * @var \App\Models\User
     */
    public $owner;

    /**
     * @var \App\Models\Dto\TotpDto|\App\Models\Dto\HotpDto
     */
    public $otpDto;

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
