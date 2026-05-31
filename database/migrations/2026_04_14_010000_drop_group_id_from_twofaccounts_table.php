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
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        if (! Schema::hasColumn('twofaccounts', 'group_id')) {
            return;
        }

        Schema::table('twofaccounts', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['group_id']);
            }
        });

        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->dropColumn('group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        if (Schema::hasColumn('twofaccounts', 'group_id')) {
            return;
        }

        Schema::table('twofaccounts', function (Blueprint $table) {
            $table->unsignedInteger('group_id')
                ->after('id')
                ->nullable();
        });

        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('twofaccounts', function (Blueprint $table) {
                $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
            });
        }
    }
};
