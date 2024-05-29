<?php

/**
 * The MIT License (MIT)
 * Copyright (c) 2024 Bubka
 * Copyright (c) 2024 Anthony Rappa
 * Copyright (c) 2017 Yaakov Dahan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and
 * associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial
 * portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
 * LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace App\Console\Commands;

use App\Models\AuthLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PurgeAuthLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $signature = '2fauth:purge-log';

    /**
     * The console command description.
     *
     * @var string
     */
    public $description = 'Delete all authentication log entries older than the configurable amount of days (see env vars).';

    /**
     * Execute the console command.
     */
    public function handle() : void
    {
        $retentionTime = config('2fauth.config.authLogRetentionTime');
        $retentionTime = is_numeric($retentionTime) ? (int) $retentionTime : 365;
        $date          = now()->subDays($retentionTime)->format('Y-m-d H:i:s');

        AuthLog::where('login_at', '<', $date)
            ->orWhere('logout_at', '<', $date)
            ->delete();

        Log::info('Authentication log purged');

        $this->components->info('Authentication log purged successfully.');
    }
}
