<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEncryptedColumnsToText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ('sqlite' !== config('database.default')) {
            
            Schema::table('twofaccounts', function (Blueprint $table) {
                $table->text('account')->change();
            });

            Schema::table('twofaccounts', function (Blueprint $table) {
                $table->text('uri')->change();
            });
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
            //
        });
    }
}
