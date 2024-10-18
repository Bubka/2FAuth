<?php

namespace App\Console\Commands\Utils;

use App\Facades\IconStore;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

trait ResetTrait
{
    /**
     * Reset icons
     */
    protected function resetIcons() : void
    {
        $this->deleteIcons();
        $this->generateIcons();
    }

    /**
     * Delete all icons
     */
    protected function deleteIcons() : void
    {
        IconStore::clear();

        $this->line('Existing icons deleted');
    }

    /**
     * Generate icons for seeded accounts
     */
    protected function generateIcons() : void
    {
        $icons = collect();
        $icons->push(['amazon.png', base64_decode(DemoIcons::AMAZON)]);
        $icons->push(['apple.png', base64_decode(DemoIcons::APPLE)]);
        $icons->push(['dropbox.png', base64_decode(DemoIcons::DROPBOX)]);
        $icons->push(['facebook.png', base64_decode(DemoIcons::FACEBOOK)]);
        $icons->push(['github.png', base64_decode(DemoIcons::GITHUB)]);
        $icons->push(['google.png', base64_decode(DemoIcons::GOOGLE)]);
        $icons->push(['instagram.png', base64_decode(DemoIcons::INSTAGRAM)]);
        $icons->push(['linkedin.png', base64_decode(DemoIcons::LINKEDIN)]);
        $icons->push(['twitter.png', base64_decode(DemoIcons::TWITTER)]);

        $icons->each(function (array $icon) {
            IconStore::store($icon[0], $icon[1]);
        });

        $this->line('Icons regenerated');
    }

    /**
     * Reset DB
     */
    protected function resetDB(string $seeder) : void
    {
        $this->flushDB();
        $this->seedDB($seeder);
    }

    /**
     * Delete all DB tables
     */
    protected function flushDB() : void
    {
        // Reset the db
        DB::table(config('auth.passwords.users.table'))->delete();
        DB::table('oauth_access_tokens')->delete();
        DB::table('oauth_personal_access_clients')->delete();
        DB::table('oauth_refresh_tokens')->delete();
        DB::table('webauthn_credentials')->delete();
        DB::table(config('auth.passwords.webauthn.table'))->delete();
        DB::table('twofaccounts')->delete();
        DB::table('groups')->delete();
        DB::table('users')->delete();
        DB::table('options')->delete();
        DB::table('auth_logs')->delete();

        $this->line('Database cleaned');
    }

    /**
     * Seed the DB
     */
    protected function seedDB(string $seeder) : void
    {
        $this->callSilent('db:seed', [
            '--class'          => $seeder,
            '--no-interaction' => 1,
        ]);

        $this->line('Database seeded');
    }
}
