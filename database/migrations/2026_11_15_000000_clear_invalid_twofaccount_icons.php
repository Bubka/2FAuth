<?php

use App\Rules\IsIconFilename;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Icon values that are not plain filenames (e.g. path traversal strings) break the rendering of the account
        DB::table('twofaccounts')
            ->whereNotNull('icon')
            ->select(['id', 'icon'])
            ->orderBy('id')
            ->each(function ($account) {
                if (! IsIconFilename::isValid($account->icon)) {
                    DB::table('twofaccounts')->where('id', $account->id)->update(['icon' => null]);

                    Log::warning(sprintf('Migration: invalid icon value detached from account #%s', $account->id));
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
