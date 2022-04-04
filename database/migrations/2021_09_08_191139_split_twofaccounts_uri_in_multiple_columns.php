<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class SplitTwofaccountsUriInMultipleColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // as SQLITE disallow to add a not nullable column without default
        // value when altering a table we add all columns as nullable and
        // change them right after to not nullable column
        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->string('otp_type', 10)->nullable();
            $table->text('secret')->nullable();
            $table->string('algorithm', 20)->nullable();
            $table->unsignedSmallInteger('digits')->nullable();
            $table->unsignedInteger('period')->nullable();
            $table->unsignedBigInteger('counter')->nullable();
        });

        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->renameColumn('uri', 'legacy_uri');
        });
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
