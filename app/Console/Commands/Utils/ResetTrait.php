<?php

namespace App\Console\Commands\Utils;

use App\Facades\IconStore;
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
        $tables = [
            config('auth.passwords.users.table'), // password_resets
            'oauth_access_tokens',
            'oauth_auth_codes',
            'oauth_clients',
            'oauth_personal_access_clients',
            'oauth_refresh_tokens',
            'webauthn_credentials',
            config('auth.passwords.webauthn.table'), // webauthn_recoveries
            'twofaccounts',
            'groups',
            'users',
            'options',
            'icons',
            'auth_logs',
            'sessions',
            'cache',
            'cache_locks',
        ];

        foreach ($tables as $table) {
            $this->deleteTableRecords($table);
        }

        $this->line('Database cleaned');
    }

    /**
     * Delete all records from a table
     */
    protected function deleteTableRecords(string $table) : void
    {
        if (DB::getSchemaBuilder()->hasTable($table)) {
            DB::table($table)->delete();
        }
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
