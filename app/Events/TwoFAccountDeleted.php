<?php

namespace App\Events;

use App\TwoFAccount;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TwoFAccountDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $twofaccount;

    /**
     * Create a new event instance.
     *
     * @param  \App\TwoFAccount  $twofaccount
     * @return void
     */
    public function __construct(TwoFAccount $twofaccount)
    {
        $this->twofaccount = $twofaccount;
    }
}