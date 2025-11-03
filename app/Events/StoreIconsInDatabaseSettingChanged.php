<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Log;

class StoreIconsInDatabaseSettingChanged
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
        Log::info('StoreIconsInDatabaseSettingChanged event dispatched');
    }
}
