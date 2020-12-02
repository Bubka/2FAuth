<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupIdColumnToTwofaccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->unsignedInteger('group_id')
                  ->after('id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');

            $table->foreign('group_id')->references('id')->on('groups');
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
                // cannot drop foreign keys in SQLite:
                if ('sqlite' !== config('database.default')) {
                    $table->dropForeign(['group_id']);
                }
            }
        );

        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->dropColumn('group_id');
        });
    }
}