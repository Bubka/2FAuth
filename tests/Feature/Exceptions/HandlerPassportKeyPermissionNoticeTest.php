<?php

namespace Tests\Feature\Exceptions;

use App\Exceptions\Handler;
use App\Models\User;
use App\Services\ReleaseRadarService;
use ErrorException;
use Illuminate\Support\Facades\Notification;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Data\ExceptionTestData;
use Tests\FeatureTestCase;

#[CoversClass(Handler::class)]
class HandlerPassportKeyPermissionNoticeTest extends FeatureTestCase
{
    protected User $admin;

    protected function setUp() : void
    {
        parent::setUp();

        $this->admin = User::factory()->administrator()->create();        
    }

    #[Test]
    public function test_passport_key_permission_notice_returns_service_unavailable_json_response()
    {
        $this->mock(ReleaseRadarService::class)
            ->shouldReceive('manualScan')
            ->once()
            ->andThrow(new ErrorException(
                'Key file "file:///tmp/oauth-public.key" permissions are not correct',
                0,
                E_USER_NOTICE
            ));

        $response = $this->actingAs($this->admin, 'web-guard')
            ->json('GET', '/system/latestRelease');

        $response->assertStatus(503)
            ->assertJson([
                'message' => __('error.passport_key_permissions'),
                'reason'  => 'https://docs.2fauth.app/getting-started/upgrade/upgrading-to-v8/#oauth-key-files-permissions',
            ]);
    }
}