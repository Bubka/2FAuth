<?php

namespace Tests\Feature\Http\Middlewares;

use App\Http\Middleware\SetLanguage;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(SetLanguage::class)]
class SetLanguageMiddlewareTest extends TestCase
{
    const IS_FR = 'fr';

    const IS_FR_WITH_VARIANT = 'fr-CA';

    const IS_DE = 'de';

    const UNSUPPORTED_LANGUAGE = 'yy';

    const UNSUPPORTED_LANGUAGES = 'yy, ww';

    const ACCEPTED_LANGUAGES_DE_FR = 'de, fr';

    const ACCEPTED_LANGUAGES_UNSUPPORTED_DE = 'yy, de';

    const ACCEPTED_LANGUAGES_WEIGHTED_DE_FR = 'fr;q=0.4, de;q=0.7';

    const ACCEPTED_LANGUAGES_WEIGHTED_FR_VARIANTS = 'fr;q=0.4, fr-CA;q=0.7, de;q=0.5';

    const ACCEPTED_LANGUAGES_WEIGHTED_ALL_DE_FR = 'fr;q=0.4, de;q=0.7, *;q=0.9';

    /**
     * @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    #[Test]
    public function test_it_applies_fallback_locale()
    {
        Config::set('app.fallback_locale', self::IS_FR);

        $this->json('GET', '/', [], ['Accept-Language' => null]);

        $this->assertEquals(self::IS_FR, App::getLocale());
    }

    #[Test]
    public function test_it_applies_fallback_locale_if_header_ask_for_unsupported()
    {
        Config::set('app.fallback_locale', self::IS_FR);

        $this->json('GET', '/', [], ['Accept-Language' => self::UNSUPPORTED_LANGUAGE]);

        $this->assertEquals(self::IS_FR, App::getLocale());
    }

    #[Test]
    public function test_it_applies_fallback_locale_if_header_ask_for_several_unsupported()
    {
        Config::set('app.fallback_locale', self::IS_FR);

        $this->json('GET', '/', [], ['Accept-Language' => self::UNSUPPORTED_LANGUAGES]);

        $this->assertEquals(self::IS_FR, App::getLocale());
    }
    
    #[Test]
    public function test_it_applies_fallback_locale_if_header_ask_for_wildcard()
    {
        Config::set('app.fallback_locale', self::IS_FR);

        $this->json('GET', '/', [], ['Accept-Language' => '*']);

        $this->assertEquals(self::IS_FR, App::getLocale());
    }

    #[Test]
    public function test_it_applies_accepted_language_from_header()
    {
        $this->json('GET', '/', [], ['Accept-Language' => self::IS_FR]);

        $this->assertEquals(self::IS_FR, App::getLocale());
    }

    #[Test]
    public function test_it_applies_first_accepted_language_from_header()
    {
        $this->json('GET', '/', [], ['Accept-Language' => self::ACCEPTED_LANGUAGES_DE_FR]);

        $this->assertEquals('de', App::getLocale());
    }

    #[Test]
    public function test_it_applies_heaviest_language_from_header()
    {
        $this->json('GET', '/', [], ['Accept-Language' => self::ACCEPTED_LANGUAGES_WEIGHTED_DE_FR]);

        $this->assertEquals('de', App::getLocale());
    }

    #[Test]
    public function test_it_applies_heaviest_language_with_variant_from_header()
    {
        $this->json('GET', '/', [], ['Accept-Language' => self::ACCEPTED_LANGUAGES_WEIGHTED_FR_VARIANTS]);

        $this->assertEquals('fr', App::getLocale());
    }

    #[Test]
    public function test_it_ignores_unsupported_language_from_header()
    {
        $this->json('GET', '/', [], ['Accept-Language' => self::ACCEPTED_LANGUAGES_UNSUPPORTED_DE]);

        $this->assertEquals('de', App::getLocale());
    }

    #[Test]
    public function test_user_preference_overrides_header()
    {
        $this->user = new User;
        $this->user['preferences->lang'] = self::IS_FR;
        
        $this->actingAs($this->user)->json('GET', '/', [], ['Accept-Language' => self::IS_DE]);

        $this->assertEquals(self::IS_FR, App::getLocale());
    }

    #[Test]
    public function test_user_preference_applies_header()
    {
        $this->user = new User;
        $this->user['preferences->lang'] = 'browser';
        
        $this->actingAs($this->user)->json('GET', '/', [], ['Accept-Language' => self::IS_DE]);

        $this->assertEquals(self::IS_DE, App::getLocale());
    }

    #[Test]
    public function test_user_preference_overrides_fallback()
    {
        Config::set('app.fallback_locale', self::IS_DE);

        $this->user = new User;
        $this->user['preferences->lang'] = self::IS_FR;
        
        $this->actingAs($this->user)->json('GET', '/', [], ['Accept-Language' => null]);

        $this->assertEquals(self::IS_FR, App::getLocale());
    }
}
