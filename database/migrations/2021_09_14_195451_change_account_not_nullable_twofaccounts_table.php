<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class ChangeAccountNotNullableTwofaccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();

        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->renameColumn('uri', 'legacy_uri');
        });

        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->text('account')->nullable(false)->change();
            $table->string('service')->nullable()->change();
            $table->string('otp_type', 10)->nullable(false)->change();
            $table->text('secret')->nullable(false)->change();
            $table->string('algorithm', 20)->nullable(false)->change();
            $table->unsignedSmallInteger('digits')->nullable(false)->change();
        });

        // Apply migration 'AlterEncryptedColumnsToText' even to sqlite base
        if ('sqlite' === $driver) {
            
            Schema::table('twofaccounts', function (Blueprint $table) {
                $table->text('account')->change();
            });

            Schema::table('twofaccounts', function (Blueprint $table) {
                $table->text('legacy_uri')->change();
            });
        }
        

        $twofaccounts = DB::table('twofaccounts')->select('id', 'legacy_uri')->get();
        $settingService = resolve('App\Services\SettingService');

        foreach ($twofaccounts as $twofaccount) {
            try {
                $legacy_uri = $settingService->get('useEncryption') ? Crypt::decryptString($twofaccount->legacy_uri) : $twofaccount->legacy_uri;
                $token = \OTPHP\Factory::loadFromProvisioningUri($legacy_uri);

                $affected = DB::table('twofaccounts')
                    ->where('id', $twofaccount->id)
                    ->update([
                        'otp_type'  => get_class($token) === 'OTPHP\TOTP' ? 'totp' : 'hotp',
                        'secret'    => $settingService->get('useEncryption') ? Crypt::encryptString($token->getSecret()) : $token->getSecret(),
                        'algorithm' => $token->getDigest(),
                        'digits'    => $token->getDigits(),
                        'period'    => $token->hasParameter('period') ? $token->getParameter('period') : null,
                        'counter'   => $token->hasParameter('counter') ? $token->getParameter('counter') : null
                    ]);
            }
            catch(Exception $ex)
            {
                Log::error($ex->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->renameColumn('legacy_uri', 'uri');
        });
    }
}
