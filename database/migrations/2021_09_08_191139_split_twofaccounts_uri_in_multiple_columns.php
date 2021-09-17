<?php

use App\Classes\Options;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Crypt;

class SplitTwofaccountsUriInMultipleColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->string('otp_type', 10);
            $table->text('secret');
            $table->string('algorithm', 20);
            $table->unsignedTinyInteger('digits');
            $table->unsignedInteger('period')->nullable();
            $table->unsignedBigInteger('counter')->nullable();
        });

        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->renameColumn('uri', 'legacy_uri');
        });
        

        $twofaccounts = DB::table('twofaccounts')->select('id', 'legacy_uri')->get();

        foreach ($twofaccounts as $twofaccount) {
            try {
                $legacy_uri = Options::get('useEncryption') ? Crypt::decryptString($twofaccount->legacy_uri) : $twofaccount->legacy_uri;
                $token = \OTPHP\Factory::loadFromProvisioningUri($legacy_uri);

                $affected = DB::table('twofaccounts')
                    ->where('id', $twofaccount->id)
                    ->update([
                        'otp_type'  => get_class($token) === 'OTPHP\TOTP' ? 'totp' : 'hotp',
                        'secret'    => Options::get('useEncryption') ? Crypt::encryptString($token->getSecret()) : $token->getSecret(),
                        'algorithm' => $token->getDigest(),
                        'digits'    => $token->getDigits(),
                        'period'    => $token->hasParameter('period') ? $token->getParameter('period') : null,
                        'counter'   => $token->hasParameter('counter') ? $token->getParameter('counter') : null
                    ]);
            }
            catch(Exception $ex)
            {
                // We leave the record as is
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
            $table->dropColumn('otp_type');
        });

        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->dropColumn('secret');
        });

        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->dropColumn('algorithm');
        });

        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->dropColumn('digits');
        });

        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->dropColumn('period');
        });
        
        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->dropColumn('counter');
        });

        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->renameColumn('legacy_uri', 'uri');
        });
    }
}
