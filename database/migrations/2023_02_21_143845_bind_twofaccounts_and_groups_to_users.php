<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')
                  ->after('id')
                  ->nullable();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')
                  ->after('id')
                  ->nullable();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        if ($legacySingleUser = DB::table('users')->first()) {
            DB::table('twofaccounts')->update(['user_id' => $legacySingleUser->id]);
            DB::table('groups')->update(['user_id' => $legacySingleUser->id]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::whenTableHasColumn('twofaccounts', 'user_id', function (Blueprint $table) {
            // cannot drop foreign keys in SQLite:
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['user_id']);
            }
            $table->dropColumn('user_id');
        });

        Schema::whenTableHasColumn('groups', 'user_id', function (Blueprint $table) {
            // cannot drop foreign keys in SQLite:
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['user_id']);
            }
            $table->dropColumn('user_id');
        });
    }
};
