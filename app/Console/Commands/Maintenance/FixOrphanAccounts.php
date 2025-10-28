<?php

namespace App\Console\Commands\Maintenance;

use App\Models\TwoFAccount;
use Illuminate\Console\Command;

/**
 * @codeCoverageIgnore
 */
class FixOrphanAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '2fauth:fix-orphan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set owner of orphan resources';

    /**
     * Indicates whether the command should be shown in the Artisan command list.
     *
     * @var bool
     */
    protected $hidden = true;

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

        $this->line('Trying to fix them...');

        foreach ($twofaccounts as $twofaccount) {
            if ($twofaccount->legacy_uri === __('error.indecipherable')) {
                $this->error(sprintf('Account #%d cannot be deciphered', $twofaccount->id));
            } else {
                try {
                    // Get a consistent account
                    $twofaccount->fillWithURI($twofaccount->legacy_uri, false, true);
                    $twofaccount->save();

                    $this->info(sprintf('Account #%d fixed', $twofaccount->id));
                } catch (\Exception $ex) {
                    $this->error(sprintf('Error while updating account #%d', $twofaccount->id));
                }
            }
        }

        $this->line('Task completed');
    }
}
