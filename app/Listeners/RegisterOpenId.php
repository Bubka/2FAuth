<?php

namespace App\Listeners;

use App\Providers\Socialite\OpenId;
use SocialiteProviders\Manager\SocialiteWasCalled;

class RegisterOpenId
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('openid', OpenId::class);
    }
}
