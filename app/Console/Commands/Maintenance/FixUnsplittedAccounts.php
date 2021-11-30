<?php

namespace App\Console\Commands\Maintenance;

use App\TwoFAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

/**
 * @codeCoverageIgnore
 */
class FixUnsplittedAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '2fauth:fix-unsplitted-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Try to fix accounts that haven\t been splitted during SplitTwofaccountsUriInMultipleColumns migration';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        if (!Schema::hasColumn('twofaccounts', 'legacy_uri')) {
            $this->comment('2fauth:fix-unsplitted-accounts is useful only after SplitTwofaccountsUriInMultipleColumns migration ran');
            return;
        }
        else $this->line('Fetching accounts...');

        $twofaccounts = TwoFAccount::where('otp_type', '')
                        ->where('secret', '')
                        ->where('algorithm', '')
                        ->where('digits', 0)
                        ->whereNull('period')
                        ->whereNull('counter')
                        ->get();

        $this->line(sprintf('%d inconsistent accounts found', $twofaccounts->count()));

        if ($twofaccounts->count() == 0) {
            $this->info('Nothing to fix');
            return;
        }

        $this->line('Try to fix them...');
        $twofaccountService = resolve('App\Services\TwoFAccountService');
        
        foreach ($twofaccounts as $twofaccount) {
            if ($twofaccount->legacy_uri === __('errors.indecipherable')) {
                $this->error(sprintf('Account #%d cannot be deciphered', $twofaccount->id));
            }
            else {
                try {
                    // Get a consistent account
                    $tempAccount = $twofaccountService->createFromUri($twofaccount->legacy_uri, false);

                    $twofaccount->otp_type  = $tempAccount->otp_type;
                    $twofaccount->secret    = $tempAccount->secret;
                    $twofaccount->algorithm = $tempAccount->algorithm;
                    $twofaccount->digits    = $tempAccount->digits;
                    $twofaccount->period    = $tempAccount->period;
                    $twofaccount->counter   = $tempAccount->counter;

                    $twofaccount->save();
                    $this->info(sprintf('Account #%d fixed', $twofaccount->id));
                }
                catch (\Exception $ex) {
                    $this->error(sprintf('Error while updating account #%d', $twofaccount->id));
                }
            }
        }

        $this->line('Task completed');
    }
}