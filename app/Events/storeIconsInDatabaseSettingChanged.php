<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Log;

class storeIconsInDatabaseSettingChanged
{
    use Dispatchable;

    /**
     * The new value of setting storeIconsInDatabase.
     */
    public bool $newValue;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(bool $newValue)
    {
        $this->newValue = $newValue;
        Log::info('storeIconsInDatabaseSettingChanged event dispatched');
    }
}
