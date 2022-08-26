<?php

namespace App\Events;

use App\Models\TwoFAccount;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TwoFAccountDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\Models\TwoFAccount
     */
    public $twofaccount;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\TwoFAccount  $twofaccount
     * @return void
     */
    public function __construct(TwoFAccount $twofaccount)
    {
        $this->twofaccount = $twofaccount;
        Log::info(sprintf('TwoFAccount #%s deleted', $twofaccount->id));
    }
}