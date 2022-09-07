<?php

namespace App\Console\Commands\Utils;

use App\Console\Commands\Utils\IconGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $filesForDelete = \Illuminate\Support\Facades\File::glob('public/icons/*.png');
        Storage::delete($filesForDelete);

        $this->line('Existing icons deleted');
    }

    /**
     * Generate icons for seeded accounts
     */
    protected function generateIcons() : void
    {
        IconGenerator::generateIcon('amazon', IconGenerator::AMAZON);
        IconGenerator::generateIcon('apple', IconGenerator::APPLE);
        IconGenerator::generateIcon('dropbox', IconGenerator::DROPBOX);
        IconGenerator::generateIcon('facebook', IconGenerator::FACEBOOK);
        IconGenerator::generateIcon('github', IconGenerator::GITHUB);
        IconGenerator::generateIcon('google', IconGenerator::GOOGLE);
        IconGenerator::generateIcon('instagram', IconGenerator::INSTAGRAM);
        IconGenerator::generateIcon('linkedin', IconGenerator::LINKEDIN);
        IconGenerator::generateIcon('twitter', IconGenerator::TWITTER);

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
        DB::table('users')->delete();
        DB::table('password_resets')->delete();
        DB::table('oauth_access_tokens')->delete();
        DB::table('oauth_personal_access_clients')->delete();
        DB::table('oauth_refresh_tokens')->delete();
        DB::table('web_authn_credentials')->delete();
        DB::table('web_authn_recoveries')->delete();
        DB::table('twofaccounts')->delete();
        DB::table('options')->delete();
        DB::table('groups')->delete();

        $this->line('Database cleaned');
    }

    /**
     * Seed the DB
     */
    protected function seedDB(string $seeder) : void
    {
        $this->callSilent('db:seed', [
            '--class' => $seeder
        ]);

        $this->line('Database seeded');
    }

}
