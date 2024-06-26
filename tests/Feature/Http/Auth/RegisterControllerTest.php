<?php

namespace Tests\Feature\Http\Auth;

use App\Facades\Settings;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Rules\ComplyWithEmailRestrictionPolicy;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

/**
 * RegisterControllerTest test class
 */
#[CoversClass(RegisterController::class)]
#[CoversClass(UserStoreRequest::class)]
#[CoversClass(ComplyWithEmailRestrictionPolicy::class)]
class RegisterControllerTest extends FeatureTestCase
{
    private const USERNAME = 'john doe';

    private const EMAIL = 'johndoe@example.org';

    private const EMAIL_NOT_IN_FILTERING_LIST = 'jane@example.org';

    private const EMAIL_EXCLUDED_BY_FILTERING_RULE = 'johndoe@anywhere.org';

    private const PASSWORD = 'password';

    private const EMAIL_FILTERING_LIST = 'johndoe@example.org|johndoe@test.org|johndoe@anywhere.org';

    private const EMAIL_FILTERING_RULE = '^[A-Za-z0-9._%+-]+@example\.org';

    public function setUp() : void
    {
        parent::setUp();
    }

    #[Test]
    public function test_register_returns_success()
    {
        DB::table('users')->delete();

        $response = $this->json('POST', '/user', [
            'name'                  => self::USERNAME,
            'email'                 => self::EMAIL,
            'password'              => self::PASSWORD,
            'password_confirmation' => self::PASSWORD,
        ])
            ->assertCreated()
            ->assertJsonStructure([
                'message',
                'name',
            ])
            ->assertJsonFragment([
                'name' => self::USERNAME,
            ]);

        $this->assertDatabaseHas('users', [
            'name'  => self::USERNAME,
            'email' => self::EMAIL,
        ]);
    }

    #[Test]
    public function test_register_with_uppercased_email_returns_success()
    {
        DB::table('users')->delete();

        $response = $this->json('POST', '/user', [
            'name'                  => self::USERNAME,
            'email'                 => strtoupper(self::EMAIL),
            'password'              => self::PASSWORD,
            'password_confirmation' => self::PASSWORD,
        ])
            ->assertCreated()
            ->assertJsonStructure([
                'message',
                'name',
            ])
            ->assertJsonFragment([
                'name' => self::USERNAME,
            ]);

        $this->assertDatabaseHas('users', [
            'name'  => self::USERNAME,
            'email' => self::EMAIL,
        ]);
    }

    #[Test]
    public function test_register_with_invalid_data_returns_validation_error()
    {
        $response = $this->json('POST', '/user', [
            'name'                  => null,
            'email'                 => self::EMAIL,
            'password'              => self::PASSWORD,
            'password_confirmation' => self::PASSWORD,
        ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_register_first_user_only_as_admin()
    {
        $this->assertDatabaseCount('users', 0);

        $response = $this->json('POST', '/user', [
            'name'                  => self::USERNAME,
            'email'                 => self::EMAIL,
            'password'              => self::PASSWORD,
            'password_confirmation' => self::PASSWORD,
        ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'name'     => self::USERNAME,
            'email'    => self::EMAIL,
            'is_admin' => true,
        ]);

        $response = $this->json('POST', '/user', [
            'name'                  => 'jane',
            'email'                 => 'jane@example.org',
            'password'              => self::PASSWORD,
            'password_confirmation' => self::PASSWORD,
        ]);

        $this->assertEquals(1, User::admins()->count());
    }

    #[Test]
    public function test_register_is_forbidden_when_registration_is_disabled()
    {
        Settings::set('disableRegistration', true);

        $this->json('POST', '/user', [
            'name'                  => self::USERNAME,
            'email'                 => self::EMAIL,
            'password'              => self::PASSWORD,
            'password_confirmation' => self::PASSWORD,
        ])
            ->assertStatus(403);
    }

    #[Test]
    public function test_register_succeeds_when_email_is_in_restricted_list()
    {
        Settings::set('restrictRegistration', true);
        Settings::set('restrictList', self::EMAIL_FILTERING_LIST);
        Settings::set('restrictRule', '');

        $this->json('POST', '/user', [
            'name'                  => self::USERNAME,
            'email'                 => self::EMAIL,
            'password'              => self::PASSWORD,
            'password_confirmation' => self::PASSWORD,
        ])
            ->assertStatus(201);
    }

    #[Test]
    public function test_register_fails_when_email_is_not_in_restricted_list()
    {
        Settings::set('restrictRegistration', true);
        Settings::set('restrictList', self::EMAIL_FILTERING_LIST);
        Settings::set('restrictRule', '');

        $this->json('POST', '/user', [
            'name'                  => self::USERNAME,
            'email'                 => self::EMAIL_NOT_IN_FILTERING_LIST,
            'password'              => self::PASSWORD,
            'password_confirmation' => self::PASSWORD,
        ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_register_succeeds_when_email_matchs_filtering_rule()
    {
        Settings::set('restrictRegistration', true);
        Settings::set('restrictList', '');
        Settings::set('restrictRule', self::EMAIL_FILTERING_RULE);

        $this->json('POST', '/user', [
            'name'                  => self::USERNAME,
            'email'                 => self::EMAIL,
            'password'              => self::PASSWORD,
            'password_confirmation' => self::PASSWORD,
        ])
            ->assertStatus(201);
    }

    #[Test]
    public function test_register_fails_when_email_does_not_match_filtering_rule()
    {
        Settings::set('restrictRegistration', true);
        Settings::set('restrictList', '');
        Settings::set('restrictRule', self::EMAIL_FILTERING_RULE);

        $this->json('POST', '/user', [
            'name'                  => self::USERNAME,
            'email'                 => self::EMAIL_EXCLUDED_BY_FILTERING_RULE,
            'password'              => self::PASSWORD,
            'password_confirmation' => self::PASSWORD,
        ])
            ->assertStatus(422);
    }

    #[Test]
    public function test_register_succeeds_when_email_is_allowed_by_list_over_regex()
    {
        Settings::set('restrictRegistration', true);
        Settings::set('restrictList', self::EMAIL_FILTERING_LIST);
        Settings::set('restrictRule', self::EMAIL_FILTERING_RULE);

        $this->json('POST', '/user', [
            'name'                  => self::USERNAME,
            'email'                 => self::EMAIL_EXCLUDED_BY_FILTERING_RULE,
            'password'              => self::PASSWORD,
            'password_confirmation' => self::PASSWORD,
        ])
            ->assertStatus(201);
    }

    #[Test]
    public function test_register_succeeds_when_email_is_allowed_by_regex_over_list()
    {
        Settings::set('restrictRegistration', true);
        Settings::set('restrictList', self::EMAIL_FILTERING_LIST);
        Settings::set('restrictRule', self::EMAIL_FILTERING_RULE);

        $this->json('POST', '/user', [
            'name'                  => self::USERNAME,
            'email'                 => self::EMAIL_NOT_IN_FILTERING_LIST,
            'password'              => self::PASSWORD,
            'password_confirmation' => self::PASSWORD,
        ])
            ->assertStatus(201);
    }
}
