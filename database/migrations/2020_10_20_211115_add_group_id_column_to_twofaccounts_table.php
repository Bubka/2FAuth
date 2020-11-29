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
        Schema::enableForeignKeyConstraints();

        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->foreignId('group_id')
                  ->after('id')
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('twofaccounts', function (Blueprint $table) {
            //$table->dropForeign('group_id');
            $table->dropColumn('group_id');
        });
    }
}
