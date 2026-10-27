<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('twofaccount_user_favorites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('twofaccount_id');

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('twofaccount_id')->references('id')->on('twofaccounts')->cascadeOnDelete();

            $table->unique(['user_id', 'twofaccount_id'], 'twofaccount_user_favorites_unique_scope');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // cannot drop foreign keys in SQLite
        Schema::whenTableHasColumn('twofaccount_user_favorites', 'user_id', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['user_id']);
            }
        });

        Schema::dropIfExists('twofaccount_user_favorites');
    }
};
