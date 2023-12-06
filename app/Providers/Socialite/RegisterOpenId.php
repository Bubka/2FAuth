<?php

namespace App\Providers\Socialite;

use SocialiteProviders\Manager\SocialiteWasCalled;

class RegisterOpenId
{
    public function __invoke(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('openid', OpenId::class);
    }
}
