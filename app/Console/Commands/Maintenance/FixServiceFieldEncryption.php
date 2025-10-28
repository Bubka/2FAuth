<?php

namespace App\Console\Commands\Maintenance;

use App\Facades\Settings;
use App\Models\TwoFAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * @codeCoverageIgnore
 */
class FixServiceFieldEncryption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '2fauth:fix-service-encryption';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and encrypt 2FA accounts Service field';

    /**
     * Indicates whether the command should be shown in the Artisan command list.
     *
     * @var bool
     */
    protected $hidden = true;

    /**
     * The name of the migration that changed the data this command will try to fix
     */
    protected string $relatedMigration = '2024_08_08_133136_encrypt_twofaccount_service_field';

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
        if (DB::table('migrations')->where('migration', $this->relatedMigration)->doesntExist()) {
            $this->fail(sprintf('Migration %s has not been run, this command cannot be used', $this->relatedMigration));
        }

        if (! Settings::get('useEncryption')) {
            $this->fail('Database encryption is Off, this command cannot be used');
        }

        $this->encryptServiceField();
    }

    /**
     * Encrypts the Service field of all TwoFAccount records
     */
    protected function encryptServiceField() : void
    {
        $twofaccounts                   = TwoFAccount::all();
        $fullyEncryptedTwofaccounts     = $twofaccounts->whereNotIn('service', [__('error.indecipherable')]);
        $partiallyEncryptedTwofaccounts = $twofaccounts->where('service', __('error.indecipherable'));

        if ($fullyEncryptedTwofaccounts->count() === $twofaccounts->count()) {
            $this->components->info('The Service field is fully encrypted');

            return;
        } else {
            $this->newLine();
            $this->components->warn('The Service field is not fully encrypted, although it should be.');
            $this->line('ID of corresponding records in the twofaccounts table:');
            $this->line($partiallyEncryptedTwofaccounts->implode('id', ', '));

            if ($this->confirm('Do you want to fix encryption of those records?', true)) {
                $error = 0;
                $partiallyEncryptedTwofaccounts->each(function (TwoFAccount $twofaccount, int $key) use (&$error) {
                    // We don't want to encrypt the Service field with a different APP_KEY
                    // than the one used to encrypt the legacy_uri, account and secret fields, the
                    // model would be inconsistent.
                    if (str_starts_with($twofaccount->legacy_uri, 'otpauth://')) {
                        $rawServiceValue      = $twofaccount->getRawOriginal('service');
                        $twofaccount->service = $rawServiceValue;
                        $twofaccount->save();
                        $this->components->task(sprintf('Fixing twofaccount record with ID #%s', $twofaccount->id));
                    } else {
                        $error += 1;
                        $this->components->task(sprintf('Fixing twofaccount record with ID #%s', $twofaccount->id), function () {
                            return false;
                        });
                        $this->components->error('Wrong encryption key: The current APP_KEY cannot decipher already encrypted fields, encrypting the Service field with this key would lead to inconsistent data encryption');
                    }
                });

                $this->newLine();

                if ($error > 0) {
                    $this->error(sprintf('%s record%s could not be fixed, see log above for details.', $error, $error > 1 ? 's' : ''));
                }

                // $this->line('Task completed');
            } else {
                $this->components->warn('No fix applied.');
                $this->line('You can re-run this command at any time to fix inconsistent records.');
            }

            return;
        }
    }
}
