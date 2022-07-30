<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use App\Facades\Settings;

class ChangeNullableInTwofaccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $twofaccounts = DB::table('twofaccounts')->select('id', 'legacy_uri')->get();

        foreach ($twofaccounts as $twofaccount) {
            try {
                $legacy_uri = Settings::get('useEncryption') ? Crypt::decryptString($twofaccount->legacy_uri) : $twofaccount->legacy_uri;
                $token = \OTPHP\Factory::loadFromProvisioningUri($legacy_uri);

                $affected = DB::table('twofaccounts')
                    ->where('id', $twofaccount->id)
                    ->update([
                        'otp_type'  => get_class($token) === 'OTPHP\TOTP' ? 'totp' : 'hotp',
                        'secret'    => Settings::get('useEncryption') ? Crypt::encryptString($token->getSecret()) : $token->getSecret(),
                        'algorithm' => $token->getDigest(),
                        'digits'    => $token->getDigits(),
                        'period'    => $token->hasParameter('period') ? $token->getParameter('period') : null,
                        'counter'   => $token->hasParameter('counter') ? $token->getParameter('counter') : null
                    ]);
            }
            catch(Exception $ex)
            {
                Log::error($ex->getMessage());
                Log::error("TwoFAccount with id #" . $twofaccount->id . " could not be splited");
            }
        }

        $twofaccounts = DB::table('twofaccounts')
            ->whereNull('account')
            ->orWhereNull('otp_type')
            ->orWhereNull('secret')
            ->orWhereNull('algorithm')
            ->orWhereNull('digits')
            ->get();

        foreach ($twofaccounts as $twofaccount) {

            $affected = DB::table('twofaccounts')
            ->where('id', $twofaccount->id)
            ->update([
                'account'  => $twofaccount->account === null ? 'account (invalid)' : $twofaccount->account + ' (invalid)',
                'otp_type'  => $twofaccount->otp_type === null ? 'totp' : $twofaccount->otp_type,
                'secret'    => $twofaccount->secret === null ? 'secret' : $twofaccount->secret,
                'algorithm' => $twofaccount->algorithm === null ? 'sha1' : $twofaccount->algorithm,
                'digits'    => $twofaccount->digits === null ? 6 : $twofaccount->digits,
            ]);
        }

        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->text('account')->nullable(false)->change();
            $table->string('service')->nullable()->change();
            $table->string('otp_type', 10)->nullable(false)->change();
            $table->text('secret')->nullable(false)->change();
            $table->string('algorithm', 20)->nullable(false)->change();
            $table->unsignedSmallInteger('digits')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
