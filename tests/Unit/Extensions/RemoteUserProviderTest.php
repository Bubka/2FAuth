<?php

namespace Tests\Unit\Extensions;

use Tests\TestCase;
use App\Extensions\RemoteUserProvider;


/**
 * @covers \App\Extensions\RemoteUserProvider
 */
class RemoteUserProviderTest extends TestCase
{
    public function test_retreiving_a_user_returns_a_non_persisted_user_instance()
    {
        $provider = new RemoteUserProvider;

        $user = $provider->retrieveById([
            'user' => 'testUser',
            'email' => 'test@example.org'
        ]);

        $this->assertInstanceOf('\App\Models\User', $user);
        $this->assertEquals(false, $user->exists);
    }
}