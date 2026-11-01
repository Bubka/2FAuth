<?php

namespace Tests\Feature\Permissions;

use App\Facades\Settings;
use App\Models\TwoFAccount;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\OtpTestData;
use Tests\FeatureTestCase;

/**
 * A PAT issued before SSO-only was enabled must stop authenticating to the API
 * when PAT usage is not allowed.
 */
#[CoversNothing]
class PatUsageWithSsoOnlyTest extends FeatureTestCase
{
    protected User $user;

    protected TwoFAccount $twofaccount;

    protected function setUp() : void
    {
        parent::setUp();

        Artisan::call('passport:keys', ['--force' => true]);

        $this->user        = User::factory()->create();
        $this->twofaccount = TwoFAccount::factory()->for($this->user)->create([
            'secret' => OtpTestData::SECRET,
        ]);
    }

    /**
     * Issue a PAT through the application endpoint while PAT usage is still permitted.
     */
    private function issuePat() : string
    {
        Settings::set('useSsoOnly', false);
        Settings::set('allowPatWhileSsoOnly', false);

        Artisan::call('passport:client', [
            '--personal'       => true,
            '--name'           => config('app.name'),
            '--provider'       => 'users',
            '--no-interaction' => true,
        ]);

        $token = $this->actingAs($this->user, 'web-guard')
            ->json('POST', '/oauth/personal-access-tokens', ['name' => 'RandomTokenName'])
            ->assertOk()
            ->json('accessToken');

        // Drop the guard user cached by actingAs() so the next request authenticates from the Bearer header only.
        $this->app['auth']->forgetGuards();

        return $token;
    }

    #[Test]
    public function test_previously_issued_pat_is_rejected_when_useSsoOnly_and_pat_not_allowed()
    {
        $token = $this->issuePat();

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id)
            ->assertOk()
            ->assertJsonPath('secret', OtpTestData::SECRET);

        $this->app['auth']->forgetGuards();

        Settings::set('useSsoOnly', true);
        Settings::set('allowPatWhileSsoOnly', false);

        // The PAT management endpoint is already forbidden by the policy...
        $this->actingAs($this->user, 'web-guard')
            ->json('GET', '/oauth/personal-access-tokens')
            ->assertForbidden();

        $this->app['auth']->forgetGuards();

        // ...but the API must not accept the old PAT either.
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id)
            ->assertForbidden()
            ->assertJsonMissingPath('secret');
    }

    #[Test]
    public function test_previously_issued_pat_still_works_when_useSsoOnly_and_pat_allowed()
    {
        $token = $this->issuePat();

        Settings::set('useSsoOnly', true);
        Settings::set('allowPatWhileSsoOnly', true);

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('GET', '/api/v1/twofaccounts/' . $this->twofaccount->id)
            ->assertOk();
    }
}
