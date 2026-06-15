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

    public TwoFAccount $twofaccount;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TwoFAccount $twofaccount)
    {
        $this->twofaccount = $twofaccount;
        Log::info(sprintf('TwoFAccount #%s deleted', $twofaccount->id));
    }
}
