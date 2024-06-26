<?php

namespace Tests\Unit\Listeners;

use App\Listeners\RegisterOpenId;
use App\Providers\Socialite\OpenId;
use Illuminate\Support\Facades\Event;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;
use Laravel\Socialite\SocialiteManager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SocialiteProviders\Manager\SocialiteWasCalled;
use Tests\TestCase;

/**
 * RegisterOpenIdTest test class
 */
#[CoversClass(RegisterOpenId::class)]
class RegisterOpenIdTest extends TestCase
{
    #[Test]
    public function test_it_registers_openId_driver()
    {
        /** @var SocialiteManager $socialite */
        $socialite = app(SocialiteFactory::class);

        $driver = $socialite->driver('openid');

        $this->assertInstanceOf(OpenId::class, $driver);
    }

    #[Test]
    public function test_RegisterOpenId_listen_to_SocialiteWasCalled_event()
    {
        Event::fake();

        Event::assertListening(
            SocialiteWasCalled::class,
            RegisterOpenId::class
        );
    }
}
