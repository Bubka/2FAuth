<?php

namespace App\Console\Commands;

use App\Models\OtpLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PurgeOtpLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $signature = '2fauth:purge-otp-log';

    /**
     * The console command description.
     *
     * @var string
     */
    public $description = 'Delete all OTP generation log entries older than the configurable amount of days (see env vars).';

    /**
     * Execute the console command.
     */
    public function handle() : void
    {
        $retentionTime = config('2fauth.config.otpLogRetentionTime');
        $retentionTime = is_numeric($retentionTime) ? (int) $retentionTime : 365;
        $date          = now()->subDays($retentionTime)->format('Y-m-d H:i:s');

        $log = OtpLog::all();
        OtpLog::where('generated_at', '<', $date)
            ->delete();
        $log = OtpLog::all();
        Log::info('OTP log purged');

        $this->components->info('OTP log purged successfully.');
    }
}
